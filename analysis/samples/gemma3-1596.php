

<?php

// Database configuration (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';

// ------------------ Forgot Password Function ------------------

/**
 * Resets a user's password based on their email.
 *
 * @param string $email The email address of the user.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword($email)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    // 2. Fetch User Data
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $stmt = $pdo->prepare("SELECT id, password_reset_token, created_at FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // User not found
    }

    // 3. Generate a Unique Token (Important for security - can use a library for better randomness)
    $token = bin2hex(random_bytes(32));  // Create a cryptographically secure random token

    // 4. Update User Data with Token
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_created_at = :created_at WHERE id = :id");
    $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':created_at', time(), PDO::PARAM_INT);
    $stmt->execute();


    // 5. Send Password Reset Email (Implement your email sending logic here)
    $resetLink = "http://yourdomain.com/reset-password?token=$token&id=$user['id']"; // Build the URL
    sendPasswordResetEmail($email, $resetLink);

    return true;
}


// ------------------ Helper Functions (Implement these) ------------------

/**
 *  Sends a password reset email.  This is where you integrate with your email sending service.
 *
 * @param string $email      The user's email address.
 * @param string $resetLink  The URL for the password reset link.
 */
function sendPasswordResetEmail($email, $resetLink)
{
    // *** Replace this with your actual email sending code ***
    // This is just a placeholder.  Use a library like PHPMailer or SwiftMailer
    // to properly send emails.

    $subject = "Password Reset Request";
    $body = "Please click the following link to reset your password:
" . $resetLink;

    // Example (replace with your email sending implementation)
    // $to = $email;
    // $headers = "From: your_email@example.com\r
";
    // mail($to, $subject, $body, $headers);

    echo "Password reset email sent to: " . $email . "
"; // Simulate sending - replace with actual mail functionality.
}



// ------------------ Example Usage (Testing - Remove in production!) ------------------

// Replace with a valid email address from your database
$emailToReset = "testuser@example.com"; //  Replace with a real email or test email

if (forgotPassword($emailToReset)) {
    echo "Password reset process initiated for " . $emailToReset . "
";
} else {
    echo "Failed to initiate password reset for " . $emailToReset . "
";
}


?>
