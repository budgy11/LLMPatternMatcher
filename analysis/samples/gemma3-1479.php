
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// This file assumes you have a database connection established and a `users` table
// with a `password` column.  You'll need to adapt this to your specific setup.

// Database credentials -  IMPORTANT: NEVER HARDCODE THIS IN A PRODUCTION ENVIRONMENT
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

/**
 * Forgets a user's password by sending a reset password link.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was successfully generated and sent, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate the email address.  This is crucial for security.
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided for password reset: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a unique, time-based token.  Important for security.
  $token = bin2hex(random_bytes(32)); // Use random_bytes for strong randomness

  // 3. Hash the token (optional but HIGHLY recommended for security).
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4.  Store the token and user ID in the database.  Use prepared statements to prevent SQL injection.
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for better error handling
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    return false;
  }

  $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
  $stmt->bindParam(':user_id', $user_id); // Replace $user_id with the actual user ID from your system.
  $stmt->bindParam(':token', $token);
  $stmt->bindParam(':expires_at', date('Y-m-d H:i:s', time() + (2 * 24 * 60 * 60))); // Token expires in 2 days

  if ($stmt->execute()) {
    // 5. Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com";

    if (mail($to, $subject, $message, $headers)) {
      return true;
    } else {
      // Email failed to send, delete the entry from the database
      $pdo->prepare("DELETE FROM password_resets WHERE token = :token AND user_id = :user_id")->execute();
      error_log("Email failed to send for token: " . $token);
      return false;
    }
  } else {
    error_log("Error inserting reset token into the database: " . $e->getMessage());
    return false;
  }
}


// Example Usage (This would be called from a form submission)
// Assuming you have a form that collects the email address
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  if (forgotPassword($email)) {
    echo "Password reset email has been sent to $email.";
  } else {
    echo "Failed to generate password reset link.";
  }
}
*/


?>
