

<?php

/**
 * Forgot Password Function
 *
 * This function handles the process of sending a password reset link
 * to the user's email address.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your application (e.g., 'https://example.com').
 * @param string $token A unique, random token to secure the reset link.
 * @param string $expiryHours The number of hours the token should be valid for (e.g., 24).
 *
 * @return bool True if the email was successfully sent, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl, string $token, int $expiryHours) {
    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email);  // Log for debugging
        return false;
    }

    // Generate the reset link
    $resetLink = $baseUrl . '/reset_password?token=' . urlencode($token) . '&expiry=' . urlencode($expiryHours);

    // Build the email content
    $subject = "Password Reset Request";
    $message = "You requested a password reset for your account.  Click on the following link to reset your password: " . $resetLink;
    $headers = "From: " .  "Your Website Name <noreply@" . $_SERVER['SERVER_NAME'] . ">" . "\r
";
    $headers .= "Reply-To: " . "noreply@" . $_SERVER['SERVER_NAME'] . "\r
";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r
";  // Important for character encoding


    // Send the email
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email); // Log for debugging
        return false;
    }
}


// Example Usage (for testing -  DO NOT USE IN PRODUCTION WITHOUT APPROPRIATE VALIDATION & SECURITY!)
// NOTE: This example does *not* handle database storage or validation.

// $email = "test@example.com"; // Replace with a real email address
// $baseUrl = "https://yourwebsite.com";
// $token = bin2hex(random_bytes(32)); // Generate a random token
// $expiryHours = 24;

// if (forgotPassword($email, $baseUrl, $token, $expiryHours)) {
//     echo "Password reset email has been sent to " . $email . ".";
// } else {
//     echo "Failed to send password reset email.";
// }

?>
