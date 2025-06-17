

<?php

// Assuming you have a database connection established
// For example:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if (!$db) {
//     die("Connection failed.");
// }

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email) {
  // Sanitize the email address (important for security!)
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 1.  Check if the email exists in the database.
  //     Use a prepared statement to prevent SQL injection!
  $stmt = $db->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();

  // Check if the query was successful
  if ($stmt->errno) {
    error_log("Error executing query: " . $stmt->error);  // Log the error for debugging
    $stmt->close();
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique, secure, and temporary password reset token.
  $resetToken = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator

  // 3.  Generate a password reset link with the token.
  $resetLink = "http://yourdomain.com/reset-password?token=" . $resetToken;

  // 4.  Update the user's record with the token.
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
  $stmt->bind_param("ss", $resetToken, $email);
  $result = $stmt->execute();

  if ($stmt->errno) {
    error_log("Error updating user: " . $stmt->error);
    $stmt->close();
    return false;
  }

  // 5.  Send an email to the user with the reset link.
  //  (Implement email sending logic here - using PHPMailer or similar)
  $subject = "Password Reset Link";
  $message = "Click the following link to reset your password: " . $resetLink;
  $headers = "From: your_email@example.com\r
";
  // sendmail_path($message, $headers); // Using sendmail_path  (ensure sendmail_path is defined and accessible)
  // Alternative email sending using PHPMailer:
  // require_once 'vendor/PHPMailer/PHPMailer.php';
  // $mail = new PHPMailer(true);
  // $mail->isSMTP();
  // $mail->Host = 'smtp.example.com';
  // $mail->Port = 587;
  // $mail->Username = 'your_email@example.com';
  // $mail->Password = 'your_password';
  // $mail->SetFrom('your_email@example.com', 'Your Application');
  // $mail->Subject = $subject;
  // $mail->Body = $message;
  // $mail->AltBody = $message;  // For non-HTML email clients
  // $mail->send();


  return true; // Success!
}

// Example usage:
$email = "test@example.com"; // Replace with the user's email

if (forgotPassword($email)) {
  echo "Password reset email sent to " . $email . ". Please check their inbox.";
} else {
  echo "Failed to generate password reset link for " . $email . ".";
}

?>
