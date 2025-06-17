    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>
</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * Generates a unique token and an email link for password reset.
 *
 * @param string $email The email address of the user.
 * @param string $baseUrl The base URL of your website (e.g., 'https://example.com').
 * @return bool True if an email was sent successfully, false otherwise.
 */
function forgot_password(string $email, string $baseUrl) {
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use random_bytes for better randomness

    // 2. Create the Reset Link
    $reset_link = $baseUrl . '/reset_password?token=' . urlencode($token);

    // 3. Prepare the Reset Email Content
    $subject = "Password Reset Request";
    $message = "Dear User,

" .
               "You requested a password reset.
" .
               "Please click on the following link to reset your password:
" .
               "<a href='" . $reset_link . "'>Reset Password</a>

" .
               "This link expires in 24 hours.

" .
               "Sincerely,
" .
               "The Example App Team";

    // 4. Send the Email (using PHPMailer - install via composer require phpmailer/phpmailer)
    if (send_email($email, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}


/**
 *  Helper function to send an email using PHPMailer
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function send_email(string $to, string $subject, string $message) {
    require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php'; // Adjust path if necessary
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

    $mail = new PHPMailer(true);

    // SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.example.com'; // Replace with your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_smtp_username'; // Replace with your SMTP username
    $mail->Password   = 'your_smtp_password';   // Replace with your SMTP password
    $mail->Port       = 587; // Or your SMTP port (e.g., 465 for SSL)
    $mail->SMART_HOST = true;

    // Set From
    $mail->setFrom('your_email@example.com', 'Example App'); // Replace with your email

    // Add CC, BCC if needed
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Headers
    $mail->addHeader('Subject', $subject);

    // Body
    $mail->msgBody($message);

    // Send the Email
    if ($mail->send()) {
        return true;
    } else {
        // Handle errors - log them or display to the user.
        error_log($mail->ErrorInfo); // Log for debugging
        return false;
    }
}



// --- Example Usage ---

// Replace with actual email address
$email = 'testuser@example.com';

// Replace with your website's base URL
$baseUrl = 'https://yourwebsite.com'; // Or http://

if (forgot_password($email, $baseUrl)) {
    echo "Password reset email sent to " . $email . " successfully.";
} else {
    echo "Failed to send password reset email to " . $email . ".";
}

?>
