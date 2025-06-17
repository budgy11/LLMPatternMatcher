

<?php

// Assume you have a database connection established (e.g., $db)
// and a table called 'users' with 'email' and 'password' columns.

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email) {
  // 1. Validate Input:  Important to prevent security vulnerabilities.
  if (empty($email)) {
    return false;
  }

  // 2. Fetch User Information
  $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $result = $stmt->execute();

  if ($result === false) {
    // Handle database errors
    error_log("Error executing query: " . $db->error);  // Log for debugging
    return false;
  }

  $user = $stmt->fetch_assoc();

  if ($user === false || empty($user)) {
    // User not found
    return false;
  }

  // 3. Generate a Token (Unique, Time-Based)
  $token = bin2hex(random_bytes(32));  // Using a secure random string.

  // 4. Hash the Token and Store it in the Database (for verification)
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $user['id'], $token, time() + 3600); // Expires in 1 hour

  if ($stmt->execute() === false) {
    // Handle database errors
    error_log("Error inserting password reset data: " . $db->error);
    return false;
  }


  // 5. Send Password Reset Email (Implement this logic)
  $to = $email;
  $subject = "Password Reset";
  $message = "Please use the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
  $headers = "From: your_website@example.com"; // Replace with your email address

  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    // Handle email sending failure
    error_log("Error sending email.");
    //  Consider more robust error handling here - e.g., logging,
    //  retry mechanisms, or informing the user.
    return false;
  }
}



/**
 * Validates the reset token and updates the user's password.
 *  This function is called AFTER the user clicks the reset link.
 *
 * @param string $token The password reset token.
 * @param string $new_password The new password.
 * @return bool True on success, false on failure.
 */
function reset_password(string $token, string $new_password) {
  // 1. Validate Token (check if it exists and is not expired)
  $stmt = $db->prepare("SELECT user_id, expiry FROM password_resets WHERE token = ? AND expiry > NOW()");
  $stmt->bind_param("s", $token);
  $result = $stmt->execute();

  if ($result === false) {
    // Handle database errors
    error_log("Error executing query: " . $db->error);
    return false;
  }

  $reset = $stmt->fetch_assoc();

  if ($reset === false || empty($reset)) {
    // Token invalid or expired
    return false;
  }

  // 2. Hash the New Password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 3. Update User Password in the Database
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("ss", $hashed_password, $user['id']);

  if ($stmt->execute() === false) {
    // Handle database errors
    error_log("Error updating user password: " . $db->error);
    return false;
  }

  // 4. Delete the Password Reset Record (Important!)
  $stmt = $db->prepare("DELETE FROM password_resets WHERE token = ?");
  $stmt->bind_param("s", $token);
  $result = $stmt->execute();
  if ($result === false) {
     // Handle database errors
    error_log("Error deleting password reset record: " . $db->error);
  }

  return true;
}



//Example Usage (Conceptual - you'll need to integrate this into your application)
// $email = "test@example.com";
// if (forgot_password($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email.";
// }

?>
