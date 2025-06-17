

<?php

/**
 * Forgot Password Function
 *
 * This function generates a temporary password and sends an email to the user
 * with a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $reset_token A unique token to track the reset request.
 * @param string $website_url The URL of your website.
 * @param string $reset_password_expiration_hours The number of hours the password reset link should be valid for.  Defaults to 24.
 *
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email, string $reset_token, string $website_url, int $reset_password_expiration_hours = 24) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging
        return false;
    }

    // 2. Generate a temporary password (you'll want a better method in production)
    $temp_password = generate_temporary_password(8); //  Generates an 8-character random password.
    
    // 3. Create the reset password URL
    $reset_url = $website_url . "/reset_password.php?token=" . urlencode($reset_token);
    
    // 4.  Prepare the email message
    $subject = "Password Reset Request";
    $message = "You have requested to reset your password.

Please click the following link to reset your password:
" . $reset_url . "

This link will expire in " . $reset_password_expiration_hours . " hours." . "

Thank you!";
    
    // 5.  Send the email (using PHPMailer or similar)
    if (send_email($email, $subject, $message)) {
        // 6.  (Optional) Store the reset token and expiration date in your database
        //  (This step is crucial for security and preventing token reuse)
        //  Example:
        //  $db = new DatabaseConnection();
        //  $query = "INSERT INTO password_resets (email, token, expiration_date) VALUES ('$email', '$reset_token', NOW() + INTERVAL $reset_password_expiration_hours HOUR)";
        //  $db->query($query);
        
        return true;
    } else {
        error_log("Failed to send email for password reset."); // Log the error
        return false;
    }
}


/**
 * Generates a temporary password.
 *  This is a simplified example. Use a cryptographically secure random number generator
 *  in a production environment.
 *
 * @param int $length The length of the password.
 * @return string The generated password.
 */
function generate_temporary_password(int $length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    $char_length = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $char = $characters[rand(0, $char_length - 1)];
        $password .= $char;
    }
    return $password;
}

/**
 * Placeholder for sending emails. Replace with your email sending function.
 *
 * @param string $to
 * @param string $subject
 * @param string $body
 * @return bool
 */
function send_email(string $to, string $subject, string $body) {
    //  Replace this with your actual email sending logic (e.g., PHPMailer, SwiftMailer)
    // Example:
    //  $mail = new PHPMailer(true);
    //  $mail->addAddress($to);
    //  $mail->setSubject($subject);
    //  $mail->msgBody($body);
    //  $mail->send();
    
    // For demonstration purposes, we'll simulate success
    //  but you MUST implement your real email sending code.
    
    // Example:
    // if (true) { // Replace with actual email sending logic
    //     return true;
    // } else {
    //     return false;
    // }

    return true; // Placeholder for testing
}

// Example Usage (for testing - replace with actual user data and website URL)
// $user_email = "test@example.com";
// $reset_token = "xyz123";
// $website_url = "http://www.example.com";

// if (forgot_password($user_email, $reset_token, $website_url)) {
//     echo "Password reset email sent successfully!";
// } else {
//     echo "Failed to send password reset email.";
// }

?>
