

<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Function to send an email (Replace with your email sending logic)
function sendEmail($email, $token) {
  // This is a placeholder.  Replace this with your actual email sending code.
  // Example: using PHPMailer:
  // https://peps.php.net/phpmailer
  // You'll need to install PHPMailer and configure it.

  // Dummy email content for demonstration
  $subject = 'Password Reset Request';
  $message = "Click the following link to reset your password: " . '<a href="' . $_SERVER['REQUEST_URI'] . '?token=' . $token . '" target="_blank">Reset Password</a>';
  $headers = "From: your_email@example.com\r
";
  mail($email, $message, $headers);

  // Or, if you're using a library like SwiftMailer:
  // ... (implementation details for SwiftMailer)

}


// Function to handle password reset request
function forgotPassword($email, $username) {
  // 1. Validate Email and Username
  if (empty($email) || empty($username)) {
    return 'Error: Email and username are required.';
  }

  // 2. Check if the user exists
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    return 'Error: Connection failed: ' . $conn->connect_error;
  }

  $result = $conn->query("SELECT id, email, username FROM users WHERE username = '$username'");

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $user_id = $user_data['id'];
    $user_email = $user_data['email'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32));  // Generates a random token.  Important for security.

    // 4.  Store the token in the database, associated with the user.
    if ($conn->query("UPDATE users SET password_reset_token = '$token' WHERE id = $user_id")) {
      // 5. Send the reset email
      sendEmail($user_email, $token);

      return "Password reset email sent to $user_email. Please check your inbox.";
    } else {
      return 'Error: Failed to update the database.';
    }

  } else {
    return 'Error: User not found.';
  }

  $conn->close();
}


// Example Usage (Simulated form submission - for demonstration only)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"]; // Replace with actual form field name
  $email = $_POST["email"]; // Replace with actual form field name

  $reset_message = forgotPassword($email, $username);
  echo $reset_message;
}
?>
