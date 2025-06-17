

<?php

// Assuming you have a database connection established.
// Replace 'database_config.php' with your actual database configuration file.
require_once 'database_config.php';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email Format (Basic Check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generate a secure, random token

  // 3.  Prepare and Execute the Query
  $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

  $sql = "UPDATE users SET password_reset_token = '$hashed_token' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    // 4.  Send Password Reset Email (Implementation Below)
    send_password_reset_email($email, $token);
    return true; // Password reset request sent successfully
  } else {
    // Error handling if the query fails
    error_log("Error updating user for password reset: " . $conn->error); // Log the error
    return false; // Failed to update user
  }
}


// --------------------  Helper Functions  --------------------

// -------------------- Password Reset Email Function --------------------
function send_password_reset_email($email, $token) {
  $to = $email;
  $subject = 'Password Reset Request';
  $message = "Click on the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($token) . '"' . ' target="_blank">' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($token) . '</a>';
  $headers = "From: your_email@example.com" . "\r
";
  mail($to, $message, $headers);
}


// -------------------- Example Usage (for demonstration - DON'T USE IN PRODUCTION WITHOUT SANITIZATION!) --------------------
// IMPORTANT: In a real application, you would handle this form submission securely.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  if (isset($email)) {
    if (forgot_password($email)) {
      echo "Password reset email has been sent to $email.";
    } else {
      echo "Failed to initiate password reset.  Please try again.";
    }
  } else {
    echo "Error: Email is missing.";
  }
}
?>
