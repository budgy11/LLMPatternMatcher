

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token, sends an email to
 * the user with a link to reset their password, and then deletes the token
 * from the database.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if the password reset email was sent successfully, false otherwise.
 */
function forgot_password(string $email, PDO $pdo) {
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

    // 2. Insert Token into Database
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expires_at', time() + 3600, PDO::PARAM_INT);  // Expires in 1 hour
    $result = $stmt->execute();

    if (!$result) {
        error_log("Error inserting password reset token: " . print_r($stmt->errorInfo(), true));
        return false;
    }

    // 3. Send Password Reset Email
    $reset_link = "https://yourdomain.com/reset-password?token=" . $token; // Replace yourdomain.com
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password:
" . $reset_link;
    $headers = "From: Your Website <admin@yourdomain.com>\r
";
    $sent = send_email($subject, $message, $headers); // Implement send_email function (see example below)

    if (!$sent) {
        // Attempt to delete the token if email fails
        delete_password_reset_token($token, $pdo);
        return false;
    }

    return true;
}


/**
 * Placeholder for the send_email function.  Replace with your email sending logic.
 *
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email(string $subject, string $message, string $headers) {
    // Replace this with your email sending logic.  This is just a placeholder.
    // Example:  Use a library like PHPMailer:  https://www.phpmailer.com/

    // This is just for demonstration and *will not* actually send emails.
    error_log("Simulating sending email: Subject: " . $subject . ", Message: " . $message);
    return true; // Simulate success
}


/**
 * Deletes a password reset token from the database.
 *
 * @param string $token The token to delete.
 * @param PDO $pdo The PDO database connection.
 */
function delete_password_reset_token(string $token, PDO $pdo) {
    try {
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        // Check if any rows were affected
        if ($pdo->lastInsertId() === 0) {
            // No rows affected, likely the token was never inserted or already deleted.
        }
    } catch (PDOException $e) {
        error_log("Error deleting password reset token: " . $e->getMessage());
    }
}

// Example Usage (Illustrative - Needs Database Setup)
/*
$pdo = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

$email = "user@example.com"; // Replace with the user's email

if (forgot_password($email, $pdo)) {
    echo "Password reset email sent to " . $email;
} else {
    echo "Failed to send password reset email.";
}
*/

?>
