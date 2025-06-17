

<?php

// Assume you have a database connection setup here
// Example: $db = new PDO("mysql:host=localhost;dbname=your_database");

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Generate a unique token (e.g., using UUID)
    $token = bin2hex(random_bytes(32)); // Securely generate a random token

    // 3. Store the token and user ID in the database
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?");
    $stmt->execute([$token, time(), $email]); // Expires after 30 minutes (or your desired time)

    // 4. Send an email to the user with a link containing the token
    //    (This is a placeholder - you'll need to implement your email sending logic)
    // Example:
    $subject = "Password Reset Link";
    $message = "Click on the following link to reset your password: " .  $_SERVER['REQUEST_SCHEME'] . "://localhost/reset_password.php?token=" . $token;  //Replace with your actual URL
    $headers = "From: your_email@example.com" . "\r
";

    // Check if email sending function exists, if not, return false
    if(function_exists('mail')){
        $result = mail("user@example.com", $subject, $message, $headers);
        if ($result === NULL) {
            return true;
        } else {
            // Handle email sending failure - log the error, etc.
            error_log("Error sending email for password reset: " . $result); //Log for debugging
            return false;
        }
    } else {
        error_log("Email sending function not found. Please implement email sending.");
        return false;
    }

    return true; // Token generated and stored successfully (awaiting user action)
}


// Example Usage (assuming you have the $db connection)
// $email = "user@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to $email.";
// } else {
//     echo "Failed to generate password reset token.";
// }

?>
