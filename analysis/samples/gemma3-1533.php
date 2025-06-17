

<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process.  It generates a unique,
 * time-based token for verification and sends an email to the user with a link
 * to reset their password.
 *
 * **Important Security Notes:**
 * - **Use a strong, random token generation method.**
 * - **Protect the token from guessing.**  This implementation uses `random_bytes` for better security.
 * - **Short Token Lifespan:**  The token should expire after a short period (e.g., 30 minutes)
 *   to minimize security risks. This implementation allows you to customize the expiry time.
 * - **HTTPS Only:**  Always use HTTPS to protect the token during transmission.
 * - **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.
 * - **Consider 2FA:**  Enhance security further with two-factor authentication.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if the process started successfully, false otherwise (e.g., user not found).
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email (basic) -  More robust validation is recommended
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided.");  // Log for debugging
        return false;
    }

    // 2. Check if the user exists (replace with your database query)
    // This is a placeholder - Replace with your actual database query
    //  Example:
    //  $user = getUserById($email);
    //  if (!$user) {
    //      return false;
    //  }

    // Simulate user existence (for example purposes only)
    $user = ['id' => 123, 'email' => $email]; // Example user

    // 3. Generate a unique, time-based token
    $token = generateUniqueToken();

    // 4.  Store the token (database) - Replace with your actual database update
    // Replace with your database update query.  This is a placeholder.
    // Example (replace with your actual database connection and query):
    // $result = updateTokenToDatabase($user['id'], $token, $expiryTime);

    // Simulate token database update
    $user['token'] = $token;
    $user['expiry'] = time() + (30 * 60); // Token expiry: 30 minutes
    $user['updated_at'] = time();

    // 5. Build the reset password link
    $resetLink = generateResetLink($token);

    // 6. Send the email
    if (!sendResetPasswordEmail($email, $resetLink)) {
        error_log("Failed to send reset password email."); //Log for debugging
        return false;
    }

    // 7. Return True (Success)
    return true;
}


/**
 * Generates a unique, time-based token
 *
 * This function uses `random_bytes` for a more secure random number generation.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // 32 bytes = 256 bits
}

/**
 * Generates the reset password link
 *
 * @param string $token The token.
 * @return string The reset password link.
 */
function generateResetLink(string $token): string
{
    return 'https://yourdomain.com/reset-password?token=' . urlencode($token);
}

/**
 * Sends the reset password email
 *
 * This function sends an email to the user with a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $resetLink The reset password link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendResetPasswordEmail(string $email, string $resetLink): bool
{
    //  Replace with your email sending logic
    // Example using PHPMailer (install and configure PHPMailer)
    // $mail = new PHPMailer\PHPMailer\PHPMailer();
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->SMTPAuth = true;
    // $mail->Username = 'your_username';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email@example.com', 'Your Application Name');
    // $mail->addAddress($email);
    // $mail->Subject = 'Password Reset';
    // $mail->msgHtml('<html><body><p>Click <a href="' . $resetLink . '" target="_blank">here</a> to reset your password.</p></body></html>');
    // $mail->send();

    // Placeholder -  Simulate success
    return true;
}


// Example Usage (for testing) -  Remove this in your actual application
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    if (isset($_GET['reset'])) {  // Triggered by a button click, etc.
        if (forgotPassword($email)) {
            echo "Password reset request initiated.  Check your email.";
        } else {
            echo "Password reset request failed. Please try again.";
        }
    }
}

?>
