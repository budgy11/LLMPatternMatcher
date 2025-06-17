

<?php

/**
 * Forgot Password Function
 *
 * This function allows users to reset their password.
 * It generates a unique, secure token and sends an email with a link to 
 * a password reset page.
 *
 * @param string $email The email address of the user.
 * @param string $token  A unique token to associate with the password reset request.
 * @param string $baseUrl The base URL of the password reset page.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $token, string $baseUrl)
{
    // Validate email and token (important for security)
    if (empty($email) || empty($token)) {
        error_log("Forgot Password: Missing email or token."); // Log for debugging
        return false;
    }

    // Build the reset link
    $resetLink = $baseUrl . "?token=" . urlencode($token);

    // Email setup
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click the following link to reset your password: " . $resetLink;
    $headers = "From: Your Website <admin@yourwebsite.com>"; // Replace with your email address

    // Send the email (using PHP's built-in mail function -  Consider alternatives for production)
    if (mail($to, $subject, $message, $headers)) {
        error_log("Forgot Password: Email sent to " . $email);
        return true;
    } else {
        error_log("Forgot Password: Failed to send email to " . $email);
        return false;
    }
}

// Example Usage (For testing purposes - Remove or adjust for your application)
// $email = "testuser@example.com";
// $token = "abcdef1234567890";
// $baseUrl = "http://localhost/reset_password/";  // Replace with your password reset URL

// if (forgotPassword($email, $token, $baseUrl)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }

?>
