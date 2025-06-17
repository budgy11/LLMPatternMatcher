

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique token and sends an email to the user with a
 * link to reset their password.  It also stores the token in the database
 * for verification.
 *
 * @param string $email The email address of the user.
 * @param string $baseUrl The base URL of your website.  This is used to
 *                         construct the reset link.  e.g., 'https://example.com'
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email, string $baseUrl) {
    // 1. Validate Email (Basic check - improve as needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email);
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator

    // 3. Prepare the Reset Link
    $reset_url = $baseUrl . "/reset_password?token=" . urlencode($token);

    // 4. Prepare the Email Message
    $subject = "Password Reset Request";
    $message = "Hello,

" .
               "Please click on the following link to reset your password:
" .
               "<a href=\"" . $reset_url . "\">Reset Password</a>

" .
               "If you did not request this password reset, please ignore this email.

" .
               "Sincerely,
" .
               "The " . basename(__FILE__) . " Team";

    // 5. Send the Email (Using PHPMailer -  Install with Composer: composer require phpmailer/phpmailer)
    if (validate_php_extensions('mail')) { // Check if mail extension is available
      if (mail($email, $subject, $message)) {
          return true;
      } else {
          error_log("Failed to send email to " . $email);
          return false;
      }
    } else {
      error_log("PHP mail extension is not enabled.  Please enable it to send emails.");
      return false;
    }
}



// Example Usage (replace with your database and email sending code)
//
// $email = "test@example.com";
// $baseUrl = "https://yourwebsite.com";
//
// if (forgot_password($email, $baseUrl)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email.";
// }

?>
