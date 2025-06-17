

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token, emails the user a password reset
 * link containing the token, and then deletes the token from the database.
 *
 * @param string $email The user's email address.
 * @param string $baseUrl The base URL of the website. This is important for generating
 *                        the absolute URL of the reset link.
 * @param string $resetPasswordTokenSecret  A secret key used to hash the reset password token.
 *                                      This should be a strong, randomly generated string.
 *
 * @return bool True if the reset email was sent, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl, string $resetPasswordTokenSecret) {
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log the error
        return false;
    }

    // Generate a unique token
    $resetPasswordToken = bin2hex(random_bytes(32));

    // Hash the token - Important for security!
    $hashedResetPasswordToken = hash('sha256', $resetPasswordToken);

    // Store the token in the database (replace this with your database logic)
    // This is just an example. Replace with your actual database query.
    // Assuming you have a 'users' table with a 'email' and 'reset_token' column.
    // This is only a placeholder.  Don't use this directly in production.
    $dbResult = database_execute_query("
        INSERT INTO reset_tokens (email, reset_token, created_at)
        VALUES ('" . $email . "', '" . $hashedResetPasswordToken . "', NOW())
    ");

    if (!$dbResult) {
        error_log("Database error: Unable to store reset token."); // Log the error
        return false;
    }

    // Send the password reset email
    $subject = "Password Reset";
    $message = "Please click the link below to reset your password: " . $baseUrl . "/reset-password?token=" . $resetPasswordToken;
    $headers = "From: " .  "your-email@example.com" . "\r
"; // Change this to your sender email

    if (mail($email, $subject, $message, $headers)) {
        // Remove the token from the database after sending the email
        database_execute_query("DELETE FROM reset_tokens WHERE email = '" . $email . "' AND reset_token = '" . $hashedResetPasswordToken . "'");
        return true;
    } else {
        error_log("Failed to send email."); // Log the error
        return false;
    }
}


// Example usage (for demonstration only - adapt to your application)
//  This is *not* the complete code you would use.
//  You'd integrate this into your login/registration flow.

// Important: Replace with your actual configuration
$baseUrl = "https://yourwebsite.com";
$resetPasswordTokenSecret = "your-secret-key"; // Strong, random secret

$email = "testuser@example.com"; // Replace with the user's email

if (forgotPassword($email, $baseUrl, $resetPasswordTokenSecret)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email.
";
}
?>
