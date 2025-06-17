

<?php

/**
 * Resets a user's password after a password reset request.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @param string $secretKey The unique secret key used to verify the reset request.
 * @param PDO $db PDO database object.
 * @return bool True on successful password reset, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, string $secretKey, PDO $db) {
    // 1. Validate Input (Important for security!)
    if (empty($email) || empty($newPassword) || empty($secretKey)) {
        return false; // Invalid input
    }

    // 2. Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        return false; // User not found
    }

    // 3.  Verify the Secret Key
    $stmt = $db->prepare("SELECT id FROM password_resets WHERE user_id = :user_id AND secret_key = :secret_key AND expiry_timestamp > :now");
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $stmt->bindParam(':secret_key', $secretKey, PDO::PARAM_STR);
    $stmt->bindParam(':now', time(), PDO::PARAM_INT);  // Get the current timestamp
    $stmt->execute();
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset === false) {
        return false; // Invalid secret key or expired reset link
    }


    // 4. Update the User's Password
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :user_id");
    $stmt->bindParam(':password', password_hash($newPassword, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    // 5.  Cleanup the Password Reset Record (Important!)
    $stmt = $db->prepare("DELETE FROM password_resets WHERE user_id = :user_id AND secret_key = :secret_key");
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $stmt->bindParam(':secret_key', $secretKey, PDO::PARAM_STR);
    $stmt->execute();

    return true; // Password reset successful
}


// Example Usage (assuming you have a PDO database connection called $db)
// IMPORTANT: This is just an example.  In a real application,
// you should handle errors and display user-friendly messages.


// $email = "test@example.com";
// $newPassword = "NewSecurePassword123";
// $secretKey = "someRandomSecretKey";  // Generate a random, secure key

// if (forgotPassword($email, $newPassword, $secretKey, $db)) {
//     echo "Password reset successful!  Check your email.";
// } else {
//     echo "Password reset failed. Please try again.";
// }
?>
