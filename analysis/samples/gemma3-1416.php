

<?php

/**
 *  Forgot Password Function
 *
 *  This function allows users to reset their passwords.
 *  It generates a unique token, sends an email to the user,
 *  and provides a link to reset their password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if the password reset email was sent successfully, false otherwise.
 */
function forgot_password($email)
{
    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging
        return false;
    }

    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a secure random number generator

    // 2. Store the token in the database.
    //  -  This example uses a simple array for demonstration.
    //  -  In a real application, you would use a proper database
    //     and store the token in the database along with the user ID.
    global $user_tokens; // Assuming $user_tokens is defined elsewhere (e.g., in a configuration file or global scope)
    if (!isset($user_tokens[$email])) {
        $user_tokens[$email] = ['token' => $token, 'expiry' => time() + (24 * 60 * 60) ]; // Expires in 24 hours
    } else {
        // Token already exists, you might want to clear it or regenerate it.
        // For simplicity, we just regenerate it.
        $user_tokens[$email] = ['token' => $token, 'expiry' => time() + (24 * 60 * 60) ];
    }



    // 3.  Create the reset password link.
    $reset_link =  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

    // 4. Send the email.
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on the following link:
" . $reset_link;
    $headers = "From: your_email@example.com\r
";
    mail($to, $message, $headers);

    // 5.  Logging (Optional, but good practice)
    error_log("Password reset email sent to: " . $email . " with token: " . $token);

    return true;
}


// Example Usage (for testing - don't use this in production)
//  Remove or comment out in a real application.
if (isset($_GET['email'])) {
  $email_to_reset = $_GET['email'];
  if (forgot_password($email_to_reset)) {
    echo "Password reset email sent to " . $email_to_reset;
  } else {
    echo "Failed to send password reset email.";
  }
}
?>
