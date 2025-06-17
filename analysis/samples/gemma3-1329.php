

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function resetPassword(string $email)
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided.");
    return false;
  }

  // 2. Check if user exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    return false;
  }

  $sql = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);  // 's' indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    error_log("User with email $email not found.");
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();

  // 3. Generate a unique token for the password reset.  This is *crucial* for security.
  $resetToken = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

  // 4.  Store the token in the database
  $sql = "UPDATE users SET reset_token = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $resetToken, $userId);
  if ($stmt->execute() === false) {
      error_log("Error updating user with reset token: " . $stmt->error);
      $stmt->close();
      $conn->close();
      return false;
  }

  // 5. Send the reset link to the user's email.  This is separate from the function for better control.
  //    (Implementation omitted for brevity - you'd typically use a mail function or a service like SendGrid).
  //    Example email content:
  //    "Click this link to reset your password: http://yourwebsite.com/reset_password.php?token=$resetToken"

  // 6.  Close the connection
  $conn->close();

  return true;
}


// Example Usage (for testing - should be removed from production code)
//  Don't call this from a public-facing form - this is just for demonstration.
if (isset($_POST['reset_email'])) {
  $email = $_POST['reset_email'];
  if (resetPassword($email)) {
    echo "Password reset link sent to $email";
  } else {
    echo "Failed to reset password.";
  }
}
?>
