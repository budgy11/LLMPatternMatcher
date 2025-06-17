

<?php

// Assume you have a database connection established and accessible as $db

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword) {
  // 1. Validate Input (Crucial for security)
  if (empty($email) || empty($newPassword)) {
    error_log("Forgot Password: Empty email or password provided.");
    return false;
  }

  //  You could add more validation here, like checking for valid email format
  //  using filter_var($email, FILTER_VALIDATE_EMAIL)
  //  And possibly length restrictions for the password.


  // 2.  Retrieve the user by email
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    error_log("Forgot Password: User with email '$email' not found.");
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];

  // 3. Hash the new password (VERY IMPORTANT - Never store plain text passwords!)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);


  // 4. Update the user's password
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("ss", $hashedNewPassword, $userId); // 's' indicates a string parameter
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    error_log("Forgot Password: Failed to update password for user '$email'.");
    return false;
  }

  // 5.  Optional:  Send a password reset email
  //    (See example email code below)

  // 6. Reset the statement and close the connection (good practice)
  $stmt->close();
  // $db->close();  // Close the database connection here if you're managing it.

  return true;
}



/**
 * Example Email Function (Replace with your email sending logic)
 *
 * This is a placeholder - you'll need to integrate with your email service.
 */
function sendPasswordResetEmail(string $email, string $resetToken) {
  // Implement your email sending logic here using a library like PHPMailer or similar.
  // This is just a placeholder.
  error_log("Sending password reset email to: $email with token: $resetToken"); // Log the action
  // Example using a very basic placeholder:
  $subject = "Password Reset";
  $message = "To reset your password, please visit: http://example.com/reset-password.php?token=$resetToken";
  $headers = "From: noreply@example.com"; // Replace with your actual sender address

  //  You'd typically use a function to actually send the email
  //  e.g., mail($email, $subject, $message, $headers);
}



//  --- Example Usage (FOR TESTING ONLY -  Don't expose this in production!)---
//  This code is for demonstration purposes.  Don't use this directly in a web application.
//  It's intended to show how to call the functions.

/*
$email = "testuser@example.com"; // Replace with a valid test email
$newPassword = "NewSecurePassword123";

if (forgotPassword($email, $newPassword)) {
  echo "Password reset successfully!";
} else {
  echo "Password reset failed.";
}
*/
?>
