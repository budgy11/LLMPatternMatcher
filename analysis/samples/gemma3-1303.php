

<?php

/**
 * Forgets a user's password.
 *
 * This function sends a password reset link to the user's email address.
 *
 * @param string $email The user's email address.
 * @param string $token A unique, randomly generated token to verify the request.
 * @param string $baseUrl The base URL of your website.  This is crucial for generating the reset link.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $token, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Password Reset Token (Important:  Make sure this is truly random)
    $resetToken = generateResetToken(); // Use the function defined below
    if (!$resetToken) {
        return false;
    }


    // 3.  Create the Password Reset Link
    $resetLink = $baseUrl . "/reset-password?token=" . urlencode($resetToken);

    // 4.  Send the Email (Using PHPMailer - Install: composer require phpmailer/phpmailer)
    if (!sendResetPasswordEmail($email, $resetLink)) {
        error_log("Failed to send reset password email to: " . $email); // Log for debugging
        return false;
    }


    return true;
}

/**
 * Generates a unique, random token for password reset.
 * @return string|null  A unique token, or null if generation fails.
 */
function generateResetToken() {
    try {
        return bin2hex(random_bytes(32)); // Use random_bytes for better security
    } catch (Exception $e) {
        error_log("Error generating reset token: " . $e->getMessage());
        return null;
    }
}


/**
 * Sends a password reset email to the user.
 *
 * @param string $email The user's email address.
 * @param string $resetLink The password reset link.
 * @return bool True on success, false on failure.
 */
function sendResetPasswordEmail(string $email, string $resetLink) {
    // ---  Replace with your email sending logic ---
    // This is a placeholder.  You'll need to implement your email sending.

    // Example using PHPMailer (install using composer require phpmailer/phpmailer)
    //  Requires SMTP setup (e.g., Gmail, Outlook, etc.)


    //   $mail = new PHPMailer(true);
    //   $mail->isSMTP();
    //   $mail->Host       = 'smtp.gmail.com'; // or your SMTP server
    //   $mail->SMTPAuth   = true;
    //   $mail->Username   = 'your_email@gmail.com'; // Your email address
    //   $mail->Password   = 'your_password';      // Your email password
    //   $mail->Port       = 587;                       // Port for TLS encryption
    //   $mail->SMART_HOST = true;
    //   $mail->Encoding   = PHPMailer::ENCODING_QUOTED;
    //   $mail->CharSet    = 'UTF-8';

    //   $mail->Subject    = 'Password Reset';
    //   $mail->Body       = 'Click this link to reset your password: ' . $resetLink;
    //   $mail->AltBody     = 'Password Reset: ' . $resetLink;
    //   $mail->SetFrom('your_email@gmail.com', 'Your Website Name');
    //   $mail->AddRecipient($email);
    //   $mail->isHTML(true);  // Make sure to set this to true if you're sending HTML emails
    //   $mail->send();

    // Return true if sending was successful.  In a real implementation,
    // you'd add error handling and check the return value from send().
    //For demonstration purposes, always return true.

    return true;
}



// Example Usage (in your controller or route):
// Assuming you have a form to get the email and token.
// $email = $_POST['email'];
// $token = $_POST['token'];
// $baseUrl = 'https://yourwebsite.com'; // Replace with your website's base URL.

// if (forgotPassword($email, $token, $baseUrl)) {
//     echo "Password reset email sent! Please check your inbox.";
// } else {
//     echo "Error sending password reset email.";
// }


?>
