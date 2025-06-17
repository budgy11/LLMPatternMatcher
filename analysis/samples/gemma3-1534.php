

<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you should implement robust security measures
// like rate limiting, email verification, and using secure password hashing.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $dbHost     The database host.
 * @param string $dbName     The database name.
 * @param string $dbUser     The database username.
 * @param string $dbPassword The database password.
 *
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUser, string $dbPassword)
{
  // 1. Database Connection
  try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage()); // Log the error
    return false;
  }


  // 2. Check if user exists
  try {
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // 3. Generate a unique token
      $token = bin2hex(random_bytes(32)); // Generate a random token

      // 4. Hash the new password
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      // 5. Update user's password and add token
      $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = :token, reset_token_expiry = :expiry  WHERE id = :user_id");
      $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':token', $token, PDO::PARAM_STR);
      $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600), PDO::PARAM_STR); // Token expires in 1 hour
      $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
      $stmt->execute();

      // 6. Send Password Reset Email (Placeholder - Replace with your email sending logic)
      $resetLink = "http://yourdomain.com/reset-password?token=$token";
      $subject = "Password Reset Request";
      $message = "Please reset your password: " . $resetLink;

      // In a real application, you would use a library or service
      // to send the email.  This is just a placeholder.
      // You might use PHPMailer, SwiftMailer, or integrate with a third-party service.
      // For example:
      // sendEmail($email, $subject, $message);



      return true;  // Password reset email sent
    } else {
      // User not found
      return false;
    }
  } catch (PDOException $e) {
    error_log("Database error during password reset: " . $e->getMessage());
    return false;
  }
}


// Example Usage (for testing -  DO NOT USE IN PRODUCTION WITHOUT SECURITY HARDENING)
// Replace with your database credentials and email sending logic
$email = 'testuser@example.com';
$newPassword = 'NewSecurePassword123';
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_db_user';
$dbPassword = 'your_db_password';

if (forgotPassword($email, $newPassword, $dbHost, $dbName, $dbUser, $dbPassword)) {
  echo "Password reset email has been sent. Check your email!";
} else {
  echo "Failed to reset password. Please check your email and try again, or contact support.";
}

?>
