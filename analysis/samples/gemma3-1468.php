

<?php

// Include your database connection file
require_once 'db_connect.php'; // Assuming you have a db_connect.php file with your database connection details

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password($email)
{
  global $db; // Access the database connection

  // Check if the email exists
  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $reset_token = generate_unique_token();
    $reset_timestamp = time(); // Store the timestamp when the token was generated

    // Hash the token (important for security!)
    $hashed_token = password_hash($reset_token, PASSWORD_DEFAULT);

    // Store the token in the database
    $query = "INSERT INTO password_resets (user_id, token, reset_timestamp) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $user_id, $hashed_token, $reset_timestamp);
    $stmt->execute();

    // Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click on the following link to reset your password: <a href='" . base_url . '/reset_password?token=" . urlencode($reset_token) . "'>Reset Password</a>";
    $headers = 'From: ' . get_setting('noreply_email') . "\r
"; // Use a noreply email address.  Replace with your actual setting.
    mail($to, $subject, $message, $headers);

    return true; // Password reset email sent successfully
  } else {
    return false; // User not found
  }
}

/**
 * Generates a unique token for password resets.
 *
 * @return string A unique, randomly generated token.
 */
function generate_unique_token()
{
    return bin2hex(random_bytes(32));  // Use bin2hex for a better representation
}



// Example Usage (Illustrative - You'll integrate this with your form)
// $email = $_POST['email']; // Get email from a form submission
// if (isset($email)) {
//   if (forgot_password($email)) {
//     echo "Password reset email has been sent to " . $email;
//   } else {
//     echo "User not found.";
//   }
// } else {
//   echo "Please enter your email address.";
// }
?>
