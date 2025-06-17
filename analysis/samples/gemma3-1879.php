

<?php

// Configuration (Change these to your specific setup)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Secure random token

  // 3. Prepare the query
  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($mysqli->connect_errno) {
    return "Failed to connect to database: " . $mysqli->connect_error;
  }

  $sql = "INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())";
  $stmt = $mysqli->prepare($sql);

  if (!$stmt) {
    return "Failed to prepare statement.";
  }

  $stmt->bind_param("s", $email, $token);

  if (!$stmt->execute()) {
    return "Failed to insert data: " . $stmt->error;
  }

  $stmt->close();
  $mysqli->close();

  // 4.  Send the reset email (Implementation details depend on your email sending method)
  //     This is a placeholder.  Replace with your actual email sending code.
  send_reset_email($email, $token);  // Call the function to send the email.

  return "Password reset email sent to $email.";
}


// Placeholder function for sending reset email.  REPLACE THIS with your actual email sending code.
function send_reset_email($email, $token) {
  //  This is just a demo.  In a real application, you'd use a library like PHPMailer or
  //  a service like SendGrid or Mailgun.

  $subject = 'Password Reset - Your Account';
  $message = "Click the following link to reset your password:  http://yourwebsite.com/reset_password.php?token=" . $token;
  $headers = 'From: your_email@example.com' . "\r
";

  // In a real application, you'd use mail() or a library to send the email.
  // Example using mail():
  // mail($email, $subject, $message, $headers);

  echo "Simulating sending email to $email with token $token"; // For demonstration purposes.
}

// Example Usage (For testing)
// You'll likely want to call this from a form, not directly like this.

// Example 1: Successful reset
$result = forgot_password("testuser@example.com");
echo $result . "<br><br>";

// Example 2: Invalid email
$result = forgot_password("invalid-email");
echo $result . "<br><br>";
?>
