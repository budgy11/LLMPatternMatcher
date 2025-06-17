

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique, time-based token and sends an email
 * to the user with a link to reset their password.
 *
 * @param string $email The email address of the user to reset the password for.
 * @param string $baseUrl The base URL of your website (required for constructing the reset link).
 * @return bool True if the reset link was successfully sent, false otherwise.
 */
function forgotPassword($email, $baseUrl) {
    // 1. Check if the email exists in the database (Replace with your database connection)
    if (!isValidEmail($email)) {
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a strong random number generator
    
    // 3. Store the token and user ID in the database (Replace with your database interaction)
    //  This should be done securely -  e.g., using prepared statements to prevent SQL injection
    if (!storeResetToken($token, $_SESSION['userId'])) { // Assuming you have a session variable for user ID
        return false;
    }

    // 4. Construct the reset link
    $resetLink = $baseUrl . "/reset-password?token=" . urlencode($token);

    // 5. Send the email
    if (!sendResetPasswordEmail($email, $resetLink)) {
        // Handle email sending failure - log the error, display a message, etc.
        return false;
    }

    return true;
}


/**
 *  Helper function to validate email format
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}



/**
 * Helper function to store the reset token and user ID in the database.
 *
 * @param string $token The unique token generated.
 * @param int $userId The user ID.
 * @return bool True if the token was successfully stored, false otherwise.
 */
function storeResetToken($token, $userId) {
    // Replace this with your actual database interaction.
    // Example using a hypothetical database connection:
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password"); //Replace with your credentials

    try {
        $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', date('Y-m-d H:i:s', time() + 60 * 60 * 24)); // Token expires after 24 hours
        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle database errors - log, display an error, etc.
        error_log("Error storing reset token: " . $e->getMessage());
        return false;
    }
}


/**
 * Helper function to send the reset password email.
 *
 * @param string $email The email address of the user.
 * @param string $resetLink The reset password link.
 * @return bool True if the email was successfully sent, false otherwise.
 */
function sendResetPasswordEmail($email, $resetLink) {
    // Replace with your email sending logic (e.g., using PHPMailer, SwiftMailer, or a similar library)
    // This is a placeholder example:
    $subject = "Password Reset Request";
    $message = "Click on the following link to reset your password: " . $resetLink;
    $headers = "From: your_email@example.com\r
"; // Replace with your email address
    
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
// Example usage:
$email = "test@example.com"; // Replace with the user's email
$baseUrl = "http://yourwebsite.com"; // Replace with your website's base URL

if (forgotPassword($email, $baseUrl)) {
    echo "Reset link sent to " . $email;
} else {
    echo "Failed to send reset link.";
}


?>
