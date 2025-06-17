
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

/**
 * Forgets a user's password and sends them a reset link.
 *
 * @param string $email The user's email address.
 * @param string $password The new password.
 * @param string $resetToken The unique token generated for password reset.
 * @param PDO $pdo The database connection.
 * @return bool True if the reset link was successfully sent, false otherwise.
 */
function forgot_password(string $email, string $password, string $resetToken, PDO $pdo) {
    // 1. Validate Input (Basic - Enhance with more robust validation)
    if (empty($email) || empty($password) || empty($resetToken)) {
        error_log("Forgot Password function: Missing required parameters.");
        return false;
    }

    // 2. Find the User
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email AND reset_token = :resetToken");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':resetToken', $resetToken);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("Forgot Password function: User not found with email: " . $email . " and reset token: " . $resetToken);
        return false; // User not found
    }


    // 3. Update Password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = '', last_reset = NOW() WHERE id = :id"); //reset_token reset and record last reset
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':id', $user['id']);
    $stmt->execute();

    // 4. Send Reset Link (Implementation depends on your email sending method)
    // Example - Replace with your actual email sending logic
    $resetLink = 'http://yourwebsite.com/reset_password?token=' . urlencode($resetToken); // Construct reset link
    send_reset_email($email, $resetLink); //Function to send the email.

    return true;
}

/**
 * Placeholder function to send a reset email.
 * Replace this with your actual email sending logic.
 *
 * @param string $email The user's email address.
 * @param string $resetLink The reset password link.
 */
function send_reset_email(string $email, string $resetLink) {
    // Implement your email sending logic here.
    // This is a placeholder - replace with your email sending code.
    error_log("Sending reset email to: " . $email . " with link: " . $resetLink);

    // Example (you'll need to configure an SMTP connection or use a library)
    // $to = $email;
    // $subject = 'Password Reset';
    // $message = "Click this link to reset your password: " . $resetLink;
    // $headers = "From: yourname@example.com\r
";
    // mail($to, $subject, $message, $headers);
}
?>
