

<?php

// Assume you have a database connection established and a user table.
// This is a simplified example, adjust based on your actual database setup.

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Function to reset password
function forgotPassword($email, $db) {
    // 1. Check if email exists
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // Email not found
    }

    // 2. Generate a unique token and store it (use a proper hash function)
    $token = bin2hex(random_bytes(32)); // Generate a random token (32 bytes = 256 bits)
    
    //  Store the token and expiration date in the database
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?");
    $stmt->execute([$token, date('Y-m-d H:i:s', time() + 3600), $email]); // Expire after 1 hour


    // 3.  Send a reset link to the user's email
    $resetLink = 'http://yourwebsite.com/reset_password?token=' . $token;  // Adjust URL
    $subject = 'Password Reset Link';
    $message = "Click on the following link to reset your password: " . $resetLink;

    // Implement email sending logic here (using PHPMailer, SwiftMailer, or similar)
    // Example (basic - replace with your email sending code):
    // sendEmail($email, $subject, $message); 

    // 4. Return true to indicate success (reset link sent)
    return true;
}

// Example Usage (for demonstration - this would typically come from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Validate email (basic check - you should use more robust validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        if (forgotPassword($email, $db)) {
            echo "Password reset link sent to your email.";
        } else {
            echo "Password reset link could not be generated.";
        }
    }
}
?>
