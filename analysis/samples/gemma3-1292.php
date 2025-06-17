

<?php

// IMPORTANT: This is a basic example and should be adapted to your specific needs.
// Security is critical when dealing with passwords. This example is for educational purposes
// and should NOT be used in a production environment without robust security measures.

// Database connection (replace with your actual database credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_database_user';
$dbPass = 'your_database_password';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the email from the form
  $email = $_POST["email"];

  // Validate the email (basic validation - improve this)
  if (empty($email)) {
    $error = "Please enter your email address.";
  } else {
    // Check if the email exists in the 'users' table
    $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $error = "Email not found.";
    } else {
      // Generate a password reset token (more secure than just a random string)
      $resetToken = bin2hex(random_bytes(32));

      // Prepare the reset token insertion statement
      $stmt = $db->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
      $stmt->bind_param("ss", $resetToken, $email);
      $stmt->execute();

      // Store the reset token in the database for the user
      // For now, just output a link - you would normally send an email with this link

      // Output the reset link.  This should be emailed to the user.
      $resetLink = 'http://yourwebsite.com/reset-password?token=' . $resetToken; // Replace with your website URL
      $error = "<p>Reset password link sent to your email address.</p><p>Click the link to reset your password.</p><p><a href=\"" . $resetLink . "\">Reset Password</a></p>";
    }
  }
}

// Function to reset the password (this should be called after the user clicks the reset link)
function resetPassword($token, $newPassword) {
  // 1. Validate the token.
  $stmt = $db->prepare("SELECT id, email FROM users WHERE reset_token = ?");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return "Invalid reset token.";
  }

  // 2. Hash the new password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Update the user's password in the database
  $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
  $stmt->bind_param("ss", $hashedPassword, $token);
  $stmt->execute();

  return "Password reset successfully. Please log in.";
}

?>
