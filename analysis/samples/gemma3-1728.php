

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to allow users to reset
 * their password. It sends an email to the user containing a link with
 * this token, which they can use to set a new password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your website (e.g., 'http://example.com').
 *                         This is needed to generate the reset link.
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided.");  // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 3. Store Token in Database (Replace with your database connection)
  // This is a placeholder - you must adapt to your database schema
  //  For demonstration, we'll use an array.  **DO NOT USE THIS IN PRODUCTION.**

  // In a real application, you'd use a database query to insert the token
  // associated with the user's email.
  $tokens = $_SESSION['reset_tokens'] ?? []; //Use session for demo
  $tokens[$email] = $token; //Store in session.  **FOR DEMO ONLY - NOT PRODUCTION READY**
  $_SESSION['reset_tokens'] = $tokens;



  // 4. Construct Reset Link
  $resetLink = $baseUrl . "/reset-password?token=" . urlencode($token);

  // 5. Send Email (Replace with your email sending logic)
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click the following link to reset your password: " . $resetLink;
  $headers = "From: " . "Your Website <noreply@yourwebsite.com>";

  //  Send the email (Replace with your email sending function)
  if (mail($to, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send password reset email.");  // Log for debugging
    return false;
  }
}


// Example Usage (For demonstration - this part is not part of the function)
// This is illustrative only and should not be executed directly
// in a production environment as it relies on session data
// that is not persistent.

// $baseUrl = 'http://localhost/your-website/'; // Replace with your website URL
// $email = 'testuser@example.com';

// if (forgotPassword($email, $baseUrl)) {
//   echo "Password reset email sent to " . $email . ". Check their inbox.";
// } else {
//   echo "Failed to send password reset email.";
// }


?>
