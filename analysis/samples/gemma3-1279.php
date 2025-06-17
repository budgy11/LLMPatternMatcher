

<?php

/**
 * Forgot Password Function
 *
 * This function allows a user to reset their password.
 * It generates a unique token, stores it in the database,
 * and sends an email to the user with a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @return bool True if email sent successfully, false otherwise.
 */
function forgot_password(string $email)
{
  // 1. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 2.  Create a password reset request
  $password_reset_request = new PasswordResetRequest();
  $password_reset_request->user_id = $userId; //Assuming you have a way to get the user ID based on the email
  $password_reset_request->token = $token;
  $password_reset_request->expiry_timestamp = time() + 3600; // Token expires in 1 hour

  // 3. Save the password reset request to the database
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); // Replace with your database credentials
  try {
    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiry_timestamp) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $token, $password_reset_request->expiry_timestamp]);
  } catch (PDOException $e) {
    // Handle the exception (e.g., log the error, display a message)
    error_log("Error creating password reset request: " . $e->getMessage());
    return false;
  }

  // 4.  Send an email with the password reset link
  $email_content = $emailContent = '
  <html>
  <body>
  <h1>Password Reset</h1>
  <p>Click on the following link to reset your password:</p>
  <a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($token) . '" >Reset Password</a>
  <p>This link will expire in 1 hour.</p>
  </body>
  </html>';

  $subject = 'Password Reset';
  $headers = 'From: your_email@example.com' . "\r
";
  mail($email, $email_content, $headers);

  return true;
}


//Example Usage (inside your PHP script)
// $email = 'user@example.com'; // The email address to send the reset link to.
// if (forgot_password($email)) {
//     echo "Password reset email sent to $email.";
// } else {
//     echo "Failed to send password reset email.";
// }

// --------------------
// PasswordResetRequest Class (for better organization and clarity)
class PasswordResetRequest {
    public $user_id;
    public $token;
    public $expiry_timestamp;
}
?>
