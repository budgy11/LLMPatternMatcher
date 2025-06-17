

<?php

// Database connection details (replace with your actual values)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Function to reset password
function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2.  Check if user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    error_log("Failed to connect to database: " . $conn->connect_error); // Log the error
    return false;
  }

  // Example Query: Get user by email (adapt to your schema)
  $query = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // 3. Generate a Unique Token (for security)
    $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random string

    // 4. Store Token in Database
    $insertQuery = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)"; // Token expires after 7 days
    if (!$conn->query($insertQuery)) {
      error_log("Failed to insert token: " . $conn->error);
      return false;
    }

    // 5.  Send Password Reset Email (See below for email code)
    sendResetEmail($email, $token);

    return true;
  } else {
    return false; // User not found
  }

  $conn->close();
}

// Function to send password reset email
function sendResetEmail($email, $token) {
  //  Replace with your email sending details (e.g., SMTP configuration)
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click on the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?token=' . $token . '" >Reset Password</a>';
  $headers = 'From: your_email@example.com' . "\r
";

  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send email.");
    return false;
  }
}

// Example Usage (Demonstration - DO NOT USE IN PRODUCTION without proper validation and security)
// $email = 'test@example.com';
// if (forgotPassword($email)) {
//   echo "Password reset email has been sent to $email.";
// } else {
//   echo "Failed to reset password. Please check your email or try again.";
// }

?>
