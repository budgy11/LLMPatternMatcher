

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to send an email (Requires PHP mail function)
function sendPasswordResetEmail($email, $resetToken) {
  // Implement your email sending logic here using a library like PHPMailer
  // For example:
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click on this link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$resetToken'>Reset Password</a>";
  $headers = "From: your_email@example.com";

  mail($to, $message, $headers);
}


// Function to handle password reset request
function forgotPassword($email) {
  // 1. Validate Email
  if (empty($email)) {
    return "Error: Email address is required.";
  }

  // 2. Check if email exists in the user table
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $resetToken = generateRandomToken(); // Generate a unique token
    $conn->query("UPDATE users SET reset_token = '$resetToken' WHERE id = '$user_id'");

    // Send password reset email
    sendPasswordResetEmail($email, $resetToken);

    return "Password reset email sent to $email. Please check your inbox.";

  } else {
    return "Error: Email address not found.";
  }

  $conn->close();
}


// Function to generate a random token (for security)
function generateRandomToken() {
  return bin2hex(random_bytes(32)); // Generate 32-byte random data
}

// --- Example Usage (for testing - should be handled by a form in a real application)
// if (isset($_POST['forgot_email'])) {
//   $email = $_POST['forgot_email'];
//   $result = forgotPassword($email);
//   echo $result;
// }

?>
