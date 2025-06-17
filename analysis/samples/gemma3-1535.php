

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token and sends an email to the user
 * with a link to reset their password.
 *
 * @param string $email The email address of the user requesting password reset.
 * @param string $baseUrl The base URL of your application. This is needed to construct the reset link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

    // 3.  Store the Token (Ideally in a database, but for demonstration, using an array)
    //    Important:  In a production environment, store this token in a database
    //    with a unique index and expiration time.

    // For demonstration purposes, storing in a session variable.
    // **DO NOT DO THIS IN A PRODUCTION ENVIRONMENT**
    $_SESSION['reset_token'] = $token;
    $_SESSION['reset_expiry'] = time() + 3600; // Token expires in 1 hour (3600 seconds)
    // 4. Create Reset Link
    $resetLink = $baseUrl . "/reset_password?token=" . $token;

    // 5. Send Email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: " . $resetLink;
    $headers = "From: " .  "your_email@example.com" . "\r
";
    $headers .= "Reply-To: " . "your_email@example.com" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        error_log("Password reset email sent to: " . $email); // Log for debugging
        return true;
    } else {
        error_log("Failed to send password reset email to: " . $email); // Log for debugging
        return false;
    }
}


// Example Usage (Illustrative -  This would typically be called from a form submission)
// Assume you have a form that takes the user's email address.
// This example demonstrates the function call.  In a real application, this would
// be triggered by a form submission.
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Example: Getting data from a form (simulated)
    $email = isset($_POST["email"]) ? $_POST["email"] : "";

    if ($email) {
        $baseUrl = "https://yourdomain.com"; // Replace with your application's base URL
        if (forgotPassword($email, $baseUrl)) {
            echo "Password reset email has been sent to " . $email . ". Check your inbox.";
        } else {
            echo "Failed to send password reset email.";
        }
    } else {
        echo "Please enter your email address.";
    }
}
?>
