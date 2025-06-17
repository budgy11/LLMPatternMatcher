

<?php

// This script assumes you have a database connection established.
// Replace these with your actual database credentials.
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password($email)
{
    // Sanitize input - VERY IMPORTANT
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    //  Check if the user exists
    $stmt = $GLOBALS['conn']->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // User not found
    }

    // Generate a unique token and store it in the database
    $token = bin2hex(random_bytes(32)); // Generate a random, secure token
    $stmt = $GLOBALS['conn']->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, NOW())");
    $stmt->execute([$user['id'], $token]);

    // Send the reset email (IMPLEMENT THIS PART)
    // This is just a placeholder - replace with your actual email sending logic
    $reset_link = 'https://yourdomain.com/reset_password?token=' . urlencode($token);  // Or your custom domain
    $subject = 'Password Reset Request';
    $message = "Please click the link below to reset your password: " . $reset_link;
    $headers = 'From: your_email@example.com' . "\r
";

    // IMPORTANT: Replace this with your email sending function!
    // For example:
    // mail($user['email'], $subject, $message, $headers);
    // This is a placeholder -  you'll need to implement actual email sending
    echo "Password reset email sent to: " . $user['email']; // Just for demo purposes

    return true;
}


// Example usage (from a form submission, for example):
// If you get the email from a POST request, you'd do something like:
// $email = $_POST['email'];

// $result = forgot_password($email);
// if ($result) {
//     echo "Password reset link sent to " . $email;
// } else {
//     echo "Failed to reset password. Please check your email address or contact support.";
// }


?>
