

<?php

// This is a simplified example and should be used with caution.
// In a production environment, you need to implement robust security measures
// like:
// - Strong password hashing (e.g., bcrypt or Argon2)
// - Email verification
// - Rate limiting to prevent brute-force attacks
// - Logging and monitoring
// - Using a dedicated email sending service

// Database connection (replace with your actual database details)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Check if the reset password request is valid
if (isset($_POST['email']) && !empty($_POST['email'])) {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Generate a random binary string

    // 2. Hash the token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Use password_hash for security

    // 3. Prepare the query
    $sql = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Use prepared statements for security
    $stmt->bind_param("s", $email, $hashed_token, time() + 3600); // expiry in 1 hour
    $stmt->execute();

    // 4. Send the reset email (replace with your email sending logic)
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=true&token=" . $token . "'>Reset Password</a>";
    $headers = 'From: your_email@example.com' . "\r
";
    mail($to, $subject, $message, $headers);

    echo "Password reset email sent to " . $email . ". Check your inbox.";

  } else {
    echo "Invalid email format.";
  }
}

// --- Reset Password Page (reset.php) ---
if (isset($_GET['reset']) && !empty($_GET['token'])) {
  $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING); //Sanitize token

  // 1. Verify Token
  $sql = "SELECT id FROM password_resets WHERE token = ? AND expiry > NOW()";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // 2. Update password (replace with your actual password update logic)
    // For demonstration purposes, we'll just echo the token
    echo "Reset token: " . $token;
    //  In a real application, you would redirect the user to a form to update their password
    //  and then hash and store the new password in the database.

  } else {
    echo "Invalid reset token.";
  }
}

?>
