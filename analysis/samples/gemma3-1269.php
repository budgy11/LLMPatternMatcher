

<?php

// Assume you have a database connection established here.
// This is just a placeholder, replace with your actual connection.
// Example:
// $db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

/**
 * Resets a user's password after a password reset request.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Verify email exists
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    // User not found
    return false;
  }

  $userId = $user['id'];

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 3. Store the token in the database associated with the user
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE email = ?");
  $stmt->execute([$token, time() + 3600, $email]); // Expires in 1 hour.  Adjust as needed.


  // 4.  Send password reset email.  This part is crucial.  The email *must* contain the token.
  $resetLink = "https://yourwebsite.com/reset-password?token=" . $token; // Replace with your actual domain
  // ... (Email sending logic here - see example email code below)


  return true;
}


/**
 *  Example Email Sending Logic (Replace with your actual email sending library)
 */
function sendPasswordResetEmail($to, $token, $resetLink) {
  // This is a placeholder.  Use a proper email library like PHPMailer.
  // Example:  https://github.com/PHPMailer/PHPMailer

  $subject = "Password Reset Request";
  $message = "Please click the link below to reset your password:
" . $resetLink;
  $headers = "From: yourwebsite@example.com
";

  // Example using PHP's mail function (less robust, but simple for demonstration)
  mail($to, $subject, $message, $headers);
}



// Example Usage:
// Assuming you have the $db connection established.

//  To initiate the password reset:
//  $email = "user@example.com";
//  if (forgotPassword($email)) {
//    echo "Password reset email sent to " . $email;
//  } else {
//    echo "User not found or password reset failed.";
//  }



?>
