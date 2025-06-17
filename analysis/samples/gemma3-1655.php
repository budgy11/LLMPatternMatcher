

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique, secure token and sends an email
 * to the user with a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $reset_token_prefix A prefix for the reset token (e.g., 'reset_').  Helps with organization.
 * @param int $token_lifetime (Optional) -  Time in seconds for the token to be valid. Defaults to 3600 (1 hour).
 *
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email, string $reset_token_prefix, int $token_lifetime = 3600) {
  // 1. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Use random_bytes for better security
  $token_data = [
    'token' => $token,
    'expiry' => time() + $token_lifetime
  ];

  // 2. Store the token in the database (or wherever you store user data)
  //    This example assumes you have a database connection and a 'users' table.
  //    Adapt this to your database structure.
  $db_connection = get_db_connection(); // Assuming you have a function to get your database connection
  $query = "INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$token', '$token_data[expiry]')";
  $result = $db_connection->query($query);
  if (!$result) {
    error_log("Error inserting password reset token: " . $db_connection->error); // Log errors for debugging
    return false;
  }


  // 3.  Send the password reset email
  $subject = 'Password Reset';
  $message = "Please click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
  $headers = "From: Your Website <admin@yourwebsite.com>"; // Replace with your actual email address

  if (mail($email, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Error sending password reset email to " . $email); // Log errors for debugging
    return false;
  }
}


/**
 * Example Usage (Demonstration only - Replace with your actual user authentication/database logic)
 */

// Simulate a user's email
$user_email = 'testuser@example.com';

// Generate the token
$reset_token_prefix = 'reset_';
$token = $reset_token_prefix . bin2hex(random_bytes(32));

// Example usage of the forgot_password function
if (forgot_password($user_email, $reset_token_prefix)) {
  echo "Password reset email sent to $user_email. Check their inbox!";
} else {
  echo "Failed to send password reset email.";
}


?>
