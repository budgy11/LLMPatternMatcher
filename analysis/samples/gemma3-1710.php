

<?php

/**
 * Forgets a user's password.
 *
 * This function sends a password reset link to the user's registered email.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log the error
    return false;
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 3. Store the token in the database (replace with your database connection)
  //   This is a simplified example - you'll want to use prepared statements
  //   to prevent SQL injection.
  $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your credentials
  try {
    $stmt = $db_connection->prepare("INSERT INTO password_resets (user_email, reset_token, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$email, $token]);
  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }


  // 4.  Send the password reset email
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token; // Replace with your domain

  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click the following link to reset your password: " . $reset_link;
  $headers = "From: your_email@example.com" . "\r
";

  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send email."); // Log the failure
    return false;
  }
}


// Example usage (demonstration only - don't use in production without proper validation and sanitization)
// In a real application, this would come from a user form submission.
$email = 'test@example.com';

if (forgot_password($email)) {
  echo "Password reset email sent to " . $email . "
";
} else {
  echo "Failed to send password reset email.
";
}

?>
