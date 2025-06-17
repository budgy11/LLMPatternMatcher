

<?php

// Ensure this file is part of a secure application and not directly accessible
// to prevent abuse.  Consider serving this through an API endpoint.

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgot_password(string $email)
{
    // Sanitize input - Essential for security
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    // Check if the user exists
    $stmt = $GLOBALS['db']->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // User not found
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    // Store the token in the database
    $stmt = $GLOBALS['db']->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->execute([$token, time() + 3600, $email]); // Token expires in 1 hour (adjust as needed)

    // Send a password reset email
    $subject = 'Password Reset Request';
    $headers = 'From: your_email@example.com' . "\r
";
    $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset={$token}' >here</a> to reset your password.";
    $headers .= "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r
";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        // Handle email sending failure (logging recommended)
        error_log("Failed to send password reset email for " . $email);
        return false;
    }
}

// Example usage (using a mock database for demonstration)
// This part would be replaced with your actual database interaction.

// Mock database connection (Replace with your actual PDO connection)
$GLOBALS['db'] = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);

// Example usage
$email = 'test@example.com';  // Replace with a real email address
if (forgot_password($email)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email for " . $email . "
";
}

?>
