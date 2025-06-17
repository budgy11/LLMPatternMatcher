

<?php

// Include your database connection file (e.g., database.php)
require_once 'database.php';

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Input (Important for security!)
    if (empty($email)) {
        return false;
    }

    // 2. Check if the email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // Email not found
    }

    // 3. Generate a Unique Token (for security)
    $token = bin2hex(random_bytes(32)); // Generate a secure, random token

    // 4.  Update the user's record with the token
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_expiry = :expiry, password_reset_requested = 1 WHERE id = :user_id");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600), PDO::PARAM_STR); // Token expires in 1 hour
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $result = $stmt->execute();

    if (!$result) {
        // Handle database error (e.g., log it)
        error_log("Error updating user with token: " . print_r($stmt->errorInfo(), true));
        return false;
    }

    // 5.  Send an email with the token (Implement your email sending logic here)
    // This is just an example - you'll need to adapt this to your email setup.
    $subject = 'Password Reset Request';
    $message = "Please click the following link to reset your password: " .  $_SERVER['PHP_SELF'] . "?reset_token=" . $token;
    $headers = "From: your_email@example.com" . "\r
";

    mail($email, $message, $headers); // Use mail() function.  Consider using a more robust email library.

    return true;
}


// Example usage (for testing, don't directly include this in a deployed application)
// $email = 'testuser@example.com'; // Replace with a valid email

// if (forgotPassword($email)) {
//     echo "Password reset email sent to " . $email . "
";
// } else {
//     echo "Failed to send password reset email for " . $email . "
";
// }

?>
