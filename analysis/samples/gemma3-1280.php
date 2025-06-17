

<?php

/**
 *  Forgot Password Function
 *
 *  This function sends an email with a link to reset the user's password.
 *
 *  @param string $email The email address of the user requesting a password reset.
 *  @param string $baseUrl The base URL of your website or application.
 *  @param string $resetToken  A unique token generated to secure the password reset link.
 *  @param int $expirySeconds The number of seconds the token is valid for.
 *  @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $baseUrl, string $resetToken, int $expirySeconds = 3600) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a unique token (if you don't have one already)
  //   This is crucial for security.
  if(empty($resetToken)) {
    $resetToken = bin2hex(random_bytes(32)); // Generate a secure random token
  }
  
  // 3.  Create the Password Reset Link
  $resetLink = $baseUrl . "/reset_password?token=" . urlencode($resetToken);

  // 4.  Prepare the Email Content
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click on the following link to reset your password:
" . $resetLink . "

This link will expire in " . $expirySeconds . " seconds.";
  $headers = "From: Your Website <noreply@yourwebsite.com>"; // Replace with your actual noreply email

  // 5.  Send the Email
  if (mail($to, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send password reset email to: " . $email);  // Log email sending failure
    return false;
  }
}

// Example Usage (Demonstration - Replace with your actual details)
// $email = "test@example.com";
// $baseUrl = "http://yourwebsite.com";
// $resetToken = "some_unique_token"; // Optionally pass a token if you already generated one.
// $expirySeconds = 600;

// if (forgot_password($email, $baseUrl, $resetToken, $expirySeconds)) {
//   echo "Password reset email sent successfully!";
// } else {
//   echo "Failed to send password reset email.";
// }


/**
 *  Reset Password Function (This function would be used to handle the password reset process
 *  after the user clicks the link.)
 *
 *  This is a placeholder function, you'll need to implement the actual password
 *  reset logic (e.g., update the password in the database).
 */

/**
 *  Example Reset Password Function
 *
 *  @param string $token The password reset token.
 *  @param string $newPassword The new password the user wants to set.
 *
 *  @return bool True on success, false on failure.
 */
function reset_password(string $token, string $newPassword) {
    //  Implement your password reset logic here.
    //  This would typically involve:
    //  1.  Verifying the token's validity (ensure it's not expired, etc.).
    //  2.  Updating the password in the database for the user associated with the token.
    //  3.  Deleting the token after successfully resetting the password.

    // Placeholder for demonstration
    return true; // Simulate success
}
?>
