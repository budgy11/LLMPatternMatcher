

<?php

// Include the database connection file (assuming it's named database.php)
require_once 'database.php';

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
  // Sanitize the email address to prevent SQL injection
  $email = mysqli_real_escape_string($connection, $email);

  // Check if the email exists in the users table
  $result = mysqli_query($connection, "SELECT id, username FROM users WHERE email = '$email'");

  if (mysqli_num_rows($result) > 0) {
    // Email exists, generate a unique token and store it
    $token = bin2hex(random_bytes(32));  // Generate a random token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

    // Store the token in the database (associated with the user)
    $sql = "UPDATE users SET reset_token = '$hashed_token' WHERE email = '$email'";

    if (mysqli_query($connection, $sql)) {
      // Send a password reset email (implementation depends on your email sending system)
      // This is just a placeholder - replace with your email sending code
      send_password_reset_email($email, $token);

      return true;
    } else {
      // Error sending the email or updating the database
      error_log("Error updating user with token: " . mysqli_error($connection));
      return false;
    }
  } else {
    // Email does not exist
    return false;
  }
}

/**
 * Placeholder function for sending the password reset email.
 *
 * @param string $email The user's email address.
 * @param string $token The generated reset token.
 */
function send_password_reset_email(string $email, string $token): void
{
  // Replace with your email sending logic here.
  // This is a placeholder - it does not actually send an email.
  error_log("Password reset email sent to: " . $email . " with token: " . $token);
  // Example using a basic email sending function (you'd need to configure this)
  /*
  $to = $email;
  $subject = 'Password Reset';
  $message = 'Click this link to reset your password: ' . 'http://yourwebsite.com/reset_password.php?token=' . $token;
  $headers = 'From: yourwebsite@example.com' . "\r
";

  mail($to, $message, $headers);
  */
}


// Example Usage (for testing - DO NOT use this directly in a deployed application)
//  This is just an example for demonstration and should not be directly used in a production environment.

/*
$user_email = "testuser@example.com"; // Replace with a valid email address
if (forgot_password($user_email)) {
    echo "Password reset email sent to $user_email";
} else {
    echo "Failed to generate password reset token.";
}
*/

?>
