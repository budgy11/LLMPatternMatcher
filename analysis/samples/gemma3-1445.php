

<?php

// IMPORTANT:  This is a simplified example and should be adapted for production use.
//  It's crucial to implement robust security measures to prevent vulnerabilities.

// Simulate a user database (replace with your actual database connection)
$users = [
    'john.doe@example.com' => ['password' => 'securePassword123', 'salt' => 'randomSalt'],
    'jane.smith@example.com' => ['password' => 'anotherPassword', 'salt' => 'anotherRandomSalt']
];

/**
 * Forgot Password Function
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was generated and emailed, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided.");
        return false;
    }

    // 2. Check if the user exists
    if (!isset($users[$email])) {
        error_log("User with email $email not found.");
        return false;
    }

    // 3. Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32)); // Use a cryptographically secure random token

    // 4. Store the token in the database (or another secure storage)
    //    **IMPORTANT:** Replace this with your database storage logic.
    //    This example just prints to the console.  Don't do this in production!
    //    Example database code (replace with your actual connection and query):
    //    $db = new PDO(...);
    //    $stmt = $db->prepare("INSERT INTO password_resets (user_email, token, expiry) VALUES (?, ?, ?)");
    //    $stmt->execute([$email, $resetToken, time() + 3600]); // Expires in 1 hour

    // 5. Create the reset link
    $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $resetToken;

    // 6. Send the reset link to the user's email
    //   **IMPORTANT:** Implement your email sending logic here (e.g., using PHPMailer, SwiftMailer, etc.)
    //    This example just prints to the console. Don't do this in production!
    error_log("Reset link sent to: " . $resetLink);
    // Example:
    // $emailResult = sendEmail($email, "Password Reset Link", $resetLink);
    // if ($emailResult) {
    //     return true;
    // } else {
    //     error_log("Failed to send email.");
    //     return false;
    // }


    return true; // Success (but didn't actually send the email for this simplified example)
}

// Example Usage (for testing - not for production)
$emailToReset = 'john.doe@example.com';

if (forgotPassword($emailToReset)) {
    echo "Reset link generated for " . $emailToReset . ". Check your email.";
} else {
    echo "Failed to generate reset link for " . $emailToReset . ".";
}

?>
