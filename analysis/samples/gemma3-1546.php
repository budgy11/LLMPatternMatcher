

<?php

// Database connection details - Replace with your actual values!
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword($email)
{
  // Sanitize input
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (empty($email)) {
    return false;
  }

  // Check if the email exists
  $query = "SELECT id, email FROM users WHERE email = '$email'";
  $result = mysqli_query($GLOBALS['db_host'], $query);

  if (!$result) {
    error_log("MySQLi error: " . mysqli_error($GLOBALS['db_host'])); // Log for debugging
    return false;
  }

  $user = mysqli_fetch_assoc($result);

  if (!$user) {
    // User not found
    return false;
  }

  // Generate a unique token for password reset
  $resetToken = bin2hex(random_bytes(32));

  // Hash the token for security
  $hashedToken = password_hash($resetToken, PASSWORD_DEFAULT);

  // Store the token in the database
  $query = "UPDATE users SET reset_token = '$hashedToken' WHERE email = '$email'";
  $result = mysqli_query($GLOBALS['db_host'], $query);

  if (!$result) {
    error_log("MySQLi error: " . mysqli_error($GLOBALS['db_host']));
    return false;
  }

  // Send the reset password email (Implement your email sending logic here)
  $subject = 'Password Reset';
  $message = "Click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . '?email=$email&token=$resetToken' . "'>Reset Password</a>";

  // In a real application, you'd use a proper email sending library or service.
  //  For this example, we'll just print the email.
  $to = $email;
  $headers = 'From: your_email@example.com' . "\r
";
  mail($to, $message, $headers); //  Use mail() for testing.  Replace with a proper email sending method.

  return true;
}


// Example Usage (for demonstration - replace with your actual logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (!empty($email)) {
    if (forgotPassword($email)) {
      echo "Password reset email has been sent to $email.";
    } else {
      echo "Failed to send password reset email. Please try again.";
    }
  } else {
    echo "Please enter your email address.";
  }
}

?>
