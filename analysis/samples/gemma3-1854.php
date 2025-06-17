    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assume you have a database connection established (e.g., $db)
// This is a simplified example - replace with your actual database connection
// For demonstration purposes, we'll use a hardcoded user and password.

// Example user data (replace with your database)
$users = [
    'john.doe@example.com' => 'password123',
    'jane.smith@example.com' => 'securePass456'
];

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // Validate email format (basic)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Check if the user exists
    if (isset($users[$email])) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32)); // Secure random token

        // Store the token and expiry time in the database for the user
        // (Replace this with your database logic)
        // Example:
        // $db->query("INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)");

        // For demonstration, we'll just log the token (replace with actual email sending)
        error_log("Password reset token generated for: " . $email . " - Token: " . $token);

        //  Here you would send an email containing a link with the token.
        //  Example (this is just a placeholder):
        //  $subject = "Password Reset Link";
        //  $headers = "From: noreply@example.com\r
";
        //  $body = "Click this link to reset your password: http://example.com/reset?token=$token";
        //  mail($email, $subject, $body, $headers);

        return true;
    } else {
        return false;
    }
}

// Example usage:
$emailToReset = 'john.doe@example.com';

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . " successfully." . PHP_EOL;
} else {
    echo "User not found or password reset email could not be sent." . PHP_EOL;
}

$emailToReset = 'nonexistent@example.com'; // Test with an invalid email

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . " successfully." . PHP_EOL;
} else {
    echo "User not found or password reset email could not be sent." . PHP_EOL;
}

?>
