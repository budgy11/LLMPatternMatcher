

<?php

// This is a simplified example for demonstration purposes only.
// In a production environment, you MUST implement robust security measures.

// Database connection (replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if the email exists in the user table
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, password_reset_token FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $id = $user['id'];
    $token = $user['password_reset_token'];

    // 3. Generate a new, unique token (important for security)
    $new_token = bin2hex(random_bytes(32)); 

    // 4. Update the password_reset_token with the new token
    $update_sql = "UPDATE users SET password_reset_token = '$new_token' WHERE id = '$id'";
    if ($conn->query($update_sql) === TRUE) {
      // 5. Send an email with the reset link
      $to = $email;
      $subject = 'Password Reset Link';
      $message = "Please click this link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset=" . $new_token;  // Use PHP_SELF to ensure the correct script is called.
      $headers = "From: your_email@example.com" . "\r
";
      mail($to, $message, $headers);

      return 'Password reset email sent to ' . $email . '. Please check your inbox.';
    } else {
      return 'Error updating password reset token.';
    }
  } else {
    return 'User not found.';
  }

  $conn->close();
}

// Example Usage (Handle form submission - NOT FOR PRODUCTION)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_message = forgot_password($email);
  echo $reset_message;
}

?>
