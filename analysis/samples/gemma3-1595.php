
    <input type="submit" value="Reset Password">
  </form>

  <p>If you forgot your password, enter your email address to receive a reset link.</p>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user to reset the password for.
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    global $db; // Assuming $db is your database connection

    // 1. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Generate a unique token (for security)
    $token = bin2hex(random_bytes(32)); // Use a strong random string

    // 3.  Update the user's record with the token.
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_expiry = ? WHERE email = ?");
    $stmt->execute([$token, time() + 3600, $email]);  //expiry set to 1 hour.  Adjust as needed.

    // 4. Send the password reset email
    $resetLink = "https://yourdomain.com/reset-password?token=" . urlencode($token); // Replace with your actual domain
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password: " . $resetLink;

    $headers = "From: Your Website <noreply@yourdomain.com>"; // Replace with your email address

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        // Email sending failed
        return false;
    }
}



// Example Usage (for testing - outside of the main application flow)
// $email = "test@example.com";
// if (forgotPassword($email)) {
//     echo "Password reset email sent to $email";
// } else {
//     echo "Failed to send password reset email to $email";
// }

?>
