

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Check if the email exists in the users table
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, password FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $id = $user['id'];
    $hashed_password = $user['password']; // Or generate a random token for reset link

    // 3. Generate a unique reset token (for security)
    $reset_token = bin2hex(random_bytes(32)); 

    // 4. Store the reset token with the user's ID
    $reset_sql = "INSERT INTO password_resets (user_id, token, created_at) 
                  VALUES ($id, '$reset_token', NOW())";
    if (!$conn->query($reset_sql)) {
      return "Error storing reset token.  Database error: " . $conn->error;
    }

    // 5.  Send an email with the reset link
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($reset_token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com" . "\r
";
    mail($to, $message, $headers); 

    return "Password reset link sent to your email.  Check your inbox.";

  } else {
    return "User not found with this email.";
  }
  $conn->close();
}

// --- Example Usage (for testing) ---
// $email_to_reset = "testuser@example.com"; // Replace with a valid email address for testing
// $reset_result = forgot_password($email_to_reset);
// echo $reset_result;

?>
