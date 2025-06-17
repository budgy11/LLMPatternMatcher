

<?php

// This is a simplified example and should be adapted for your specific security needs.
// It's crucial to implement robust security measures, especially for production environments.

// Database connection (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to send an email (replace with your email sending logic)
function send_reset_email($email, $token) {
  //  This is a placeholder.  Implement your email sending code here.
  //  Use a reliable email service (e.g., PHPMailer, SwiftMailer)
  //  to ensure proper email delivery.

  $subject = 'Password Reset';
  $message = "Please click the link below to reset your password:
" .
             '<a href="?reset_token=' . $token . '">Reset Password</a>';
  $headers = 'From: your_email@example.com' . "\r
";

  //  For testing, you can echo the email content
  //  echo $message . "

";
  //  In a real application, you would send this email using the $message and $headers.

  // Placeholder:  For demonstration only.  Do not use this in production!
  error_log("Reset email sent to: " . $email . " with token: " . $token);
}


function forgot_password($email) {
  // 1. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id'];
  $username = $result->fetch_assoc()['username'];

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a strong, random token

  // 3. Store the token in the database associated with the user
  $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
  $stmt->bind_param("ss", $token, $email);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // Token not stored - likely a database error
  }

  // 4. Send the reset email
  send_reset_email($email, $token);

  // 5. Close connections
  $stmt->close();
  $conn->close();

  return true; // Success
}


// Example usage (for demonstration - this would be in your form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (empty($email)) {
    echo "Please enter your email address.";
  } else {
    if (forgot_password($email)) {
      echo "Password reset email has been sent to your email address.  Please check your inbox.";
    } else {
      echo "An error occurred while processing your request.";
    }
  }
}
?>
