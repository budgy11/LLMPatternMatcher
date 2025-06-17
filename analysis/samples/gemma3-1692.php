

<?php

// Assuming you have a database connection established and stored in a variable like $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist in the database
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random string

    // 3. Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the token in the database associated with the user
    $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
    $stmt->execute([$hashed_token, time() + 60 * 60 * 24, $email]); //  Token expires after 24 hours. Adjust as needed.

    // 5. Send the password reset email (see example below)
    if (send_password_reset_email($email, $token)) {
        return true;
    } else {
        // Failed to send email -  you might want to log this.
        return false;
    }
}

/**
 * Sends a password reset email to the user.
 *
 * @param string $email The email address of the user.
 * @param string $token The password reset token.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_password_reset_email(string $email, string $token): bool
{
    // Replace with your email sending logic
    // This is just a placeholder.  Use a proper email sending library/service.
    // You should *never* embed sensitive information directly in code.

    $subject = "Password Reset Request";
    $message = "To reset your password, please click on the following link: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";

    $headers = "From: Your Website <yourwebsite@example.com>";  // Replace with your actual email address

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}


// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (!empty($email)) {
        $reset_result = forgot_password($email);

        if ($reset_result) {
            echo "<p>Password reset email has been sent to $email.</p>";
        } else {
            echo "<p>An error occurred while attempting to reset your password.</p>";
        }
    } else {
        echo "<p>Please enter your email address.</p>";
    }
}
?>
