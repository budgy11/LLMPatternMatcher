
<!-- HTML form for submission (reset_password.php or similar) -->
<form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
</form>


<?php

/**
 *  Forgot Password Function
 *
 *  This function allows a user to reset their password by sending a password reset link
 *  to their registered email address.
 */

/**
 * Forgot password function.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL for the reset link.  Typically your domain.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password($email, $baseUrl) {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log the error
        return false;
    }

    // Get the reset token
    $reset_token = generate_reset_token();

    // Hash the token
    $hashed_token = hash('sha256', $reset_token); // Use a strong hashing algorithm

    // Prepare the data for the email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click on the following link to reset your password:
" .
               "<a href='" . $baseUrl . "/reset?token=" . urlencode($reset_token) . "'>Reset Password</a>";
    $headers = "From: " . get_sender_email() . "\r
"; // Replace with your sender email

    // Send the email
    if (send_email($to, $subject, $message, $headers)) {
        // Store the token in the database (or whatever storage you use)
        store_reset_token($email, $hashed_token);
        return true;
    } else {
        error_log("Failed to send password reset email for " . $email); // Log the failure
        return false;
    }
}

/**
 * Generate a unique reset token.  This should be a random string.
 *
 * @return string The generated token.
 */
function generate_reset_token() {
    return bin2hex(random_bytes(32)); // Generates a 32-byte random string
}


/**
 * Store the reset token in the database (replace with your database logic)
 *
 * @param string $email The email address of the user.
 * @param string $hashed_token The hashed reset token.
 */
function store_reset_token($email, $hashed_token) {
    // This is a placeholder. Replace with your actual database logic.
    // Example:
    // $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class
    // $query = "INSERT INTO password_resets (email, hashed_token, created_at) VALUES ('" . $email . "', '" . $hashed_token . "', NOW())";
    // $db->query($query);
    //  You'd likely also include a timestamp to expire the token.
}


/**
 * Send an email.  This is a placeholder. Replace with your email sending logic.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $message The email body.
 * @param string $headers The email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email($to, $subject, $message, $headers) {
    // This is a placeholder.  Replace with your email sending implementation.
    // Example (using PHPMailer - you'll need to install and configure it):
    // require_once 'phpmailer/PHPMailerAutoload.php'; // Adjust path if necessary

    // $mail = new PHPMailer();
    // $mail->Mailer = "smtp";
    // $mail->SMTPDebug = 0; // Set to 1 to enable debugging
    // $mail->Host = get_smtp_host(); // Replace with your SMTP host
    // $mail->Port = get_smtp_port();    // Replace with your SMTP port
    // $mail->Username = get_smtp_username();
    // $mail->Password = get_smtp_password();
    // $mail->SetFrom(get_sender_email(), get_sender_email());
    // $mail->AddAddress($to, "");
    // $mail->Subject = $subject;
    // $mail->Body = $message;
    // $mail->AltBody = $message;

    // $result = $mail->send();

    //  Simplified placeholder return value
    return true;
}


/**
 * Get Sender Email (Replace with your actual sender email configuration)
 *
 * @return string The sender email address.
 */
function get_sender_email() {
  return "your_sender_email@example.com"; // Replace with your sender email
}

/**
 * Get SMTP Host (Replace with your SMTP server address)
 *
 * @return string The SMTP host address.
 */
function get_smtp_host() {
  return "smtp.example.com"; // Replace with your SMTP host
}

/**
 * Get SMTP Port (Replace with your SMTP port)
 *
 * @return int The SMTP port.
 */
function get_smtp_port() {
    return 587; // Common SMTP port for TLS
}

/**
 * Get SMTP Username (Replace with your SMTP username)
 *
 * @return string The SMTP username.
 */
function get_smtp_username() {
    return "your_smtp_username";
}

/**
 * Get SMTP Password (Replace with your SMTP password)
 *
 * @return string The SMTP password.
 */
function get_smtp_password() {
    return "your_smtp_password";
}


// --- Example Usage ---
// $email = "testuser@example.com";
// if (forgot_password($email, "yourdomain.com")) {
//     echo "Password reset email sent to " . $email . ". Please check their inbox.";
// } else {
//     echo "Failed to send password reset email.";
// }

?>
