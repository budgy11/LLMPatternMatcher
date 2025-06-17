

<?php

// Database connection details (Replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Forgets a user's password by sending a password reset email.
 *
 * @param string $email The email address of the user.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log invalid emails for debugging
        return false;
    }

    // 2. Check if User Exists
    $stmt = $GLOBALS['db']->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("User with email $email not found."); //Log user not found
        return false;
    }

    // 3. Generate a Unique Token (Use a more robust method in production)
    $token = bin2hex(random_bytes(32)); // More secure than rand()

    // 4. Store the Token in the Database
    $stmt = $GLOBALS['db']->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE email = ?");
    $stmt->execute([$token, time() + 60*60*24, $email]); // Token expires in 24 hours

    // 5. Send Password Reset Email (Using PHPMailer - Install with Composer: `composer require phpmailer/phpmailer`)
    if (!send_password_reset_email($email, $token);) {
        error_log("Failed to send password reset email for user $email.");
        // You might want to roll back the database update here if sending the email fails, 
        // depending on your requirements.
        return false;
    }

    return true;
}

/**
 * Sends a password reset email to the given email address with the provided token.
 *
 * @param string $email The email address to send the email to.
 * @param string $token The password reset token.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_password_reset_email(string $email, string $token): bool
{
    // Configuration for the email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($token) . '" >Reset Password</a>';
    $headers = 'From: your_email@example.com' . "\r
"; // Replace with your email
    
    // Use PHPMailer to send the email
    $mail = new PHPMailer(true);

    $mail->isSMTP();          // Set to true for SMTP
    $mail->Host           = 'smtp.example.com'; // Replace with your SMTP server
    $mail->SMTPAuth       = true;                     // Enable SMTP authentication
    $mail->Username       = 'your_username';           // SMTP username
    $mail->Password       = 'your_password';           // SMTP password
    $mail->Port           = 587;                       // TCP port to use
    $mail->SetFrom('your_email@example.com', 'Your Application Name'); // Replace with sender email
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHTML(true);  // Create HTML email body
    
    if (!$mail->Send()) {
        error_log("Failed to send email for user: " . $email); // Log email sending failure
        return false;
    }

    return true;
}


// --- Example Usage (For Testing) ---
// Replace 'test@example.com' with a valid email address
$email_to_reset = 'test@example.com';

if (forgot_password($email_to_reset)) {
    echo "Password reset email sent to $email_to_reset. Check their inbox!";
} else {
    echo "Failed to send password reset email for $email_to_reset.";
}

?>
