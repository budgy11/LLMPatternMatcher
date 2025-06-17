

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique token and sends an email with a link
 * to reset the password.
 *
 * @param string $email The email address to send the reset password link to.
 * @param string $baseUrl The base URL of your application.  This is crucial
 *                     for creating the correct reset link.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl) {
    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random string.  bin2hex() converts the bytes to a hex string.

    // 2. Create the reset password URL
    $resetUrl = $baseUrl . '/reset-password?token=' . urlencode($token);

    // 3. Prepare the email subject and body
    $subject = 'Password Reset';
    $body = "Please click the link below to reset your password:
" .
            "<a href='" . $resetUrl . "'>Reset Password</a>
" .
            "
This link will expire in 1 hour."; // Adjust expiration time as needed


    // 4.  Send the email (using PHPMailer - you'll need to install it!)
    //  Requires:  https://pecl.php.net/package/pecl-filesystem - to check if you have the filesystem extension.

    if (version_compare($PHP_VERSION, '7.2', '>=')) {
        //PHP 7.2+ - Use alternative approach
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server
        $mail->Port = 587;                // Port for SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Your email address
        $mail->Password = 'your_password';      // Your SMTP password
        $mail->SetFrom('your_email@example.com', 'Your Application Name');
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body); //For non-HTML email clients
        $mail->CharSet = 'UTF-8'; // Set character encoding
        if ($mail->send()) {
            return true;
        } else {
            error_log("PHPMailer error: " . $mail->ErrorInfo);
            return false;
        }
    } else {
        // PHP < 7.2 - Use older approach
        $to = $email;
        $headers = "From: Your Name <your_email@example.com>\r
";
        $headers .= "MIME-Version: 1.0\r
";
        $headers .= "Content-type: text/html; charset=UTF-8\r
";

        if (mail($to, $body, $headers)) {
            return true;
        } else {
            error_log("Email sending failed!");
            return false;
        }
    }

}


// Example Usage (for testing - DO NOT USE IN PRODUCTION WITHOUT SECURITY MEASURES!)
//  This is just a demonstration.  Never expose your actual email credentials or
//  the random token in the public code!

// Add these lines to your framework's routing or controller code
// To allow this example to run properly, you'll need to have a route like:
//  /reset-password/:token
// And a corresponding controller action.

// Placeholder email address. Replace with a real email address to test.
// Replace 'smtp.example.com' with your SMTP server's hostname.
// Replace 'your_email@example.com' and 'your_password' with your SMTP credentials.

//  IMPORTANT SECURITY NOTE:
//  - Do NOT store passwords directly in your application.
//  - Never expose the random token in your public code.
//  - Implement proper CSRF protection for reset password links.
//  - Consider using a more robust email sending service (e.g., SendGrid, Mailgun)
//    for production environments.


// Example usage:
// $baseUrl = 'http://localhost/my-app'; // Replace with your application's base URL
// $email = 'test@example.com'; // Replace with the user's email

// if (forgotPassword($email, $baseUrl)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }


?>
