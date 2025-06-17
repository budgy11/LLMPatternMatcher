

<?php

// This is a simplified example and should be adapted for a production environment.
// It's crucial to implement proper security measures like hashing passwords,
// preventing brute-force attacks, and handling input validation rigorously.

// Assume you have a database connection setup (e.g., $db)

function forgot_password($email) {
  // 1. Check if the email exists
  $result = mysqli_query($db, "SELECT id FROM users WHERE email = '$email'"); // Replace $db with your database connection

  if (mysqli_num_rows($result) == 0) {
    return "Email not found.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Using bin2hex for a secure random token

  // 3. Store the token and user ID in the database
  mysqli_query($db, "UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() WHERE email = '$email'");

  // 4.  Send an email with the reset link
  $to = $email;
  $subject = "Password Reset Link";
  $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";
  $headers = "From: your_email@example.com";  // Replace with your email address

  mail($to, $message, $headers);

  return "Password reset email sent to $email.";
}

// Example Usage (This would be part of a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $reset_message = forgot_password($email);
    echo $reset_message;
  }
}

?>
