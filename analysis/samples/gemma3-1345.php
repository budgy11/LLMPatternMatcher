

<?php

// Database credentials (Replace with your actual credentials)
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log invalid emails for debugging
    return false;
  }

  // 2. Check if user exists
  $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_SILENT);

  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    error_log("User with email " . $email . " not found."); // Log non-existent users
    return false;
  }


  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

  // 4.  Prepare the token setting statement
  $sql = "UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry
          WHERE email = :email";

  // 5.  Execute the update statement
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':token', $token, PDO::PARAM_STR);
  $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + (30 * 60)), PDO::PARAM_STR); // Token expires after 30 minutes
  $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);
  $result = $stmt->execute();

  if (!$result) {
    error_log("Error updating user record: " . print_r($stmt->errorInfo(), true)); // Log SQL errors
    return false;
  }


  // 6. Send Password Reset Email (Implement your email sending logic here)
  $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
  send_password_reset_email($user['email'], $reset_link);

  return true;
}


/**
 * Sends a password reset email.  This is a placeholder; replace with your actual email sending implementation.
 *
 * @param string $email The user's email address.
 * @param string $reset_link The link to the password reset page.
 */
function send_password_reset_email(string $email, string $reset_link) {
  // Implement your email sending logic here.  Example:
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click on the following link to reset your password: " . $reset_link;
  $headers = 'From: your_email@example.com' . "\r
";  // Replace with your email address

  mail($to, $message, $headers);
  error_log("Password reset email sent to " . $email); // Log email sending
}

// Example Usage:
//  $email = 'testuser@example.com'; // Replace with the user's email
//  if (forgot_password($email)) {
//      echo "Password reset email has been sent to " . $email;
//  } else {
//      echo "Failed to reset password for " . $email;
//  }


?>
