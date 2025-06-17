

<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you'd want to implement robust security measures.

// Configuration (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Connect to the database
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_SILENT); // Suppress errors for simplicity
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage()); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    try {
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // User not found
        }
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
        return false;
    }


    // 3. Generate a unique token (for security)
    $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

    // 4.  Create a reset token record in the database
    try {
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expires_at', date('Y-m-d H:i:s', time() + (2 * 24 * 60 * 60)), PDO::PARAM_STR); // Expires in 2 days
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Database insert error: " . $e->getMessage());
        return false;
    }
}


// Example Usage:
//  (This would normally be triggered from a form submission)

$email = 'test@example.com';  // Replace with the user's email

if (forgot_password($email)) {
    echo "A password reset link has been sent to $email.";
} else {
    echo "Failed to reset password.  Please check the email or contact support.";
}

?>
