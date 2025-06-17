

<?php

/**
 * Resets a user's password.
 *
 * This function generates a unique token, emails the user with a reset link,
 * and optionally sets the password in the database.
 *
 * @param string $email The email address of the user requesting the password reset.
 * @param string $newPassword The new password for the user.
 * @param string $token  A unique token generated to verify the reset request.
 * @param PDO $pdo     The database connection object.
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $token, PDO $pdo)
{
    // 1. Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Generate a unique token
    $generatedToken = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

    // 3. Update the user's record with the token
    $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = :token WHERE id = :user_id");
    $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
    $stmt->bindParam(':token', $generatedToken, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $result = $stmt->execute();

    if (!$result) {
        // Error updating the database
        return false;
    }

    // 4.  Send the reset password email (implementation left as exercise - see comments)
    //    This is just a placeholder to show where this part would go.
    sendResetPasswordEmail($email, $generatedToken);

    return true;
}

/**
 * Placeholder function to send a reset password email.
 *  This function would contain the logic to send an email with the reset link.
 *
 * @param string $email        The email address of the user.
 * @param string $resetToken The unique token generated.
 *
 * @return void
 */
function sendResetPasswordEmail(string $email, string $resetToken)
{
    //  Replace this with your actual email sending implementation.
    //  For example, you might use PHPMailer or another email library.
    //  This is just a placeholder.

    error_log("Reset password email sent to: " . $email . " with token: " . $resetToken);
    // Example using PHPMailer (requires installation and configuration)
    // $mail = new PHPMailer\PHPMailer\PHPMailer();
    // $mail->SMTPDebugEnable = true;
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->Username = 'your_username';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email', 'Your App Name');
    // $mail->Subject = 'Password Reset';
    // $mail->Body = "Click this link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . '?token=" . $resetToken . "'>Reset Password</a>";
    // $mail->AltBody = "Password reset link: Reset Password Link";
    // $mail->Send();
}


// Example Usage (for demonstration only -  DO NOT USE THIS IN PRODUCTION)
// Assuming you have a PDO connection named $pdo

// $email = 'testuser@example.com';
// $newPassword = 'NewSecurePassword123';
// $token = 'someUniqueTokenString';

// if (forgotPassword($email, $newPassword, $token, $pdo)) {
//     echo "Password reset request sent.  Check your email.";
// } else {
//     echo "Password reset request failed.";
// }


?>
