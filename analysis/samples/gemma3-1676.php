

<?php

/**
 * Forgot Password Function
 *
 * This function allows a user to reset their password by sending an email
 * with a reset link.
 *
 * @param string $email The email address of the user requesting the reset.
 * @param string $reset_token A unique token to generate for the reset link.  Use a library like UUID or generate a random string.
 * @param string $to Email address to send the reset link to.
 * @param string $from Email address to send the reset link from.
 * @param string $website_url URL of your website.
 *
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $reset_token, string $to, string $from, string $website_url): bool
{
    // 1. Sanitize and Validate Email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate Reset Link
    $reset_link = $website_url . "/reset_password?token=" . $reset_token;

    // 3. Send Email
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password:
" . $reset_link;
    $headers = "From: " . $from . "\r
";
    $headers .= "Reply-To: " . $from . "\r
";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r
";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email to " . $email); // Log email sending failure
        return false;
    }
}

// Example Usage (for testing - REMOVE in production)
if (isset($_POST['email']) && isset($_POST['reset_token'])) {
    $email = $_POST['email'];
    $reset_token = $_POST['reset_token'];
    $to = $email; // Use the posted email or a default
    $from = "your_email@example.com"; // Replace with your email
    $website_url = "https://yourwebsite.com"; // Replace with your website URL

    if (forgot_password($email, $reset_token, $to, $from, $website_url)) {
        echo "Reset link sent to " . $email;
    } else {
        echo "Failed to send reset link.";
    }
}

?>
