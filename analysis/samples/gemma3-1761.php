

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to be sent to the user's email.
 * The user can then use this token to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $token A unique, time-based token.  This is generated internally.
 * @param string $reset_url The URL to redirect the user to after they use the reset token.
 * @return bool True if the token was successfully sent, false otherwise.
 */
function forgot_password(string $email, string $token, string $reset_url): bool
{
    // 1. Validate Input (Basic - Add more robust validation as needed)
    if (empty($email)) {
        error_log("Forgot password: Empty email provided");
        return false;
    }
    if (empty($token)) {
        error_log("Forgot password: Empty token provided");
        return false;
    }

    // 2.  Store the token in the database (Replace with your actual database logic)
    // This is a simplified example - use prepared statements to prevent SQL injection!
    $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');  // Replace with your credentials
    try {
        $stmt = $db_connection->prepare("INSERT INTO forgot_passwords (email, token, expiry) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);
    } catch (PDOException $e) {
        error_log("Forgot password: Database error: " . $e->getMessage());
        return false;
    }

    // 3. Send the reset email
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password: " . $reset_url;
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
        error_log("Forgot password: Email sent to " . $email);
        return true;
    } else {
        error_log("Forgot password: Failed to send email to " . $email);
        return false;
    }
}


// Example usage (for testing - NEVER put sensitive data directly in your code)
// Replace these with actual values
$email = "testuser@example.com";  // Replace with a valid email
$token = "abcdef1234567890";
$reset_url = "https://yourwebsite.com/reset-password?token=" . urlencode($token); // Encode the token for the URL

if (forgot_password($email, $token, $reset_url)) {
    echo "Password reset email sent to " . $email;
} else {
    echo "Failed to send password reset email.";
}

?>
