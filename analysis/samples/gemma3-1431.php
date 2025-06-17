

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate the email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Fetch user data from the database
  $conn = new mysqli($host, $username, $password, $dbname);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username, password_reset_token, created_at FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];
    $password_reset_token = $user['password_reset_token'];
    $createdAt = $user['created_at'];

    // 3. Generate a unique reset token (Important for security)
    $resetToken = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

    // 4. Update the user's record with the new token
    $update_sql = "UPDATE users SET password_reset_token = '$resetToken', password_reset_token_expiry = NOW() WHERE id = $userId";
    if ($conn->query($update_sql) === TRUE) {
      // 5.  Send the reset link to the user's email
      $emailLink = base64_encode($resetToken);  // Encode token for URL

      // Construct the link to the reset password page
      $reset_url = "reset_password.php?token=$emailLink";
      
      // Send the email (Implement your email sending logic here)
      $subject = "Password Reset Link";
      $message = "Click on the following link to reset your password: " . "<a href=\"" . $reset_url . "\">Reset Password</a>";
      $headers = "From: your_email@example.com" . "\r
";

      mail($email, $message, $headers);

      return "Password reset link has been sent to your email.  Check your inbox.";
    } else {
      return "Error updating password reset token: " . $conn->error;
    }
  } else {
    return "User with email $email not found.";
  }

  // Close the connection
  $conn->close();
}


// Example usage (This would typically be triggered by a form submission)
// $email = $_POST['email'];  // Get the email from the form
// $result = forgot_password($email);
// echo $result;

?>
