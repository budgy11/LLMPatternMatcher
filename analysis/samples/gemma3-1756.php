

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the email from the form
  $email = trim($_POST['email']);

  // Validate the email format (basic check - can be enhanced)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Attempt to reset password using a token
    $token = generate_unique_token(); // Function to generate a unique token
    $reset_hash = hash('sha256', $token); // Hash the token for security

    // Prepare the SQL query
    $sql = "UPDATE users SET reset_token = '$reset_hash' WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($GLOBALS['conn'], $sql);

    if (mysqli_affected_rows($GLOBALS['conn']) > 0) {
      // Send an email with the reset link
      $subject = 'Password Reset Link';
      $headers = 'From: your_email@example.com' . "\r
";
      $message = "Click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "'>Reset Password</a>";

      mail($email, $message, $headers);

      $success = "Password reset email has been sent to $email.";
    } else {
      $error = "No user found with this email address.";
    }
  }
}
?>
