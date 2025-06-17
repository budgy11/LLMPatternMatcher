

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token and sends an email
 * with a link to reset the password.
 *
 * @param string $email The email address of the user.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Secure random bytes for token generation

    // 2. Store Token in Database (Replace with your database connection)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');  // Adjust connection details
    try {
        $stmt = $db->prepare("INSERT INTO forgot_passwords (email, token, expiry_timestamp) VALUES (:email, :token, :expiry)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', time() + (3600 * 24 * 7));  // Token expires in 7 days
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error creating forgot password token: " . $e->getMessage());
        return false;
    }

    // 3. Send Reset Password Email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click on the following link to reset your password: " .
               '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '">' .
               "Reset Password" .
               '</a>';
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email
    mail($to, $message, $headers);

    return true;
}


// Example Usage (For testing - you wouldn't typically call this directly from a web form)
// echo forgotPassword('test@example.com'); // Replace with a valid email address
?>
