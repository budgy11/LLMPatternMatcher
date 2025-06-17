

<?php

/**
 * Forgets a user's password.  This is a simplified example and
 * should be used with caution in a production environment.
 *
 * Important Security Considerations:
 * 1.  Salt and Hash:  This example uses plain passwords, which is extremely insecure.
 *     In a real application, *always* use a strong hashing algorithm
 *     (like bcrypt or Argon2) with a unique salt for each user.
 * 2.  Email Verification:  Always send an email to confirm the password reset.
 * 3.  Rate Limiting: Implement rate limiting to prevent abuse.
 * 4.  Secure Communication:  Ensure the entire process (including email communication)
 *     is done over HTTPS.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if a reset link was successfully generated and emailed, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email (basic example - improve in production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided.");  // Log for debugging
        return false;
    }

    // 2. Generate a unique, secure token (e.g., UUID)
    $token = bin2hex(random_bytes(32)); // Generate a 32-byte random token

    // 3. Store the token and user ID in a secure location (e.g., database)
    //    This example uses an in-memory array for demonstration purposes ONLY.
    //    DO NOT USE THIS IN PRODUCTION.
    //    Use a database to store the token and user ID persistently.
    if (!isset($_SESSION['reset_tokens'][$token])) { // Check if token already exists
        $_SESSION['reset_tokens'][$token] = [
            'user_id' => $email, // Store the user ID (email in this example)
            'token' => $token,
            'expiry' => time() + 3600 // Token expires in 1 hour
        ];
    }

    // 4.  Create the reset URL
    $reset_url = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

    // 5.  Send an email with the reset URL
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password:
" . $reset_url . "

This is a test email.  Please ignore if you didn't request a password reset."; // Improve the message
    $headers = 'From: your_email@example.com' . "\r
"; //  Use a real email address!
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email for password reset."); // Log the failure
        return false;
    }
}

// Example Usage (for testing - remove/comment out in production)
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    if (forgotPassword($email)) {
        echo "Reset link sent to " . $email . ".  Check your email.";
    } else {
        echo "Failed to generate reset link for " . $email;
    }
}
?>
