

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to be emailed to the user.
 * After the user confirms the password reset link, the token is invalidated.
 *
 * @param string $email The user's email address.
 * @return bool True if an email was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

  // 3. Store Token and User ID in Database (Example - Adapt to your database structure)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials

  try {
    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $stmt->bindParam(':user_id', (int)$email);  // Use (int) to ensure integer type
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', time() + (60 * 60 * 24)); // Expire in 24 hours
    $stmt->execute();

    // 4. Send Reset Email
    $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password?token=' . $token; // Build the reset link
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password:
" . $resetLink;
    $headers = "From: Your Website <admin@yourwebsite.com>\r
";

    if (mail($email, $subject, $message, $headers)) {
      return true;
    } else {
      // Log the email sending failure
      error_log("Failed to send email to " . $email);
      return false;
    }
  } catch (PDOException $e) {
    // Log the database error
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage:
// $email = 'user@example.com';
// if (forgotPassword($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email.";
// }

?>
