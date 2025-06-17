

<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param PDO $db          The database connection object.
 *
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, PDO $db) {
    // 1. Check if the email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // User not found
        return false;
    }

    // 2. Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 3. Update the user's password in the database
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        // Password update failed
        return false;
    }

    return true;
}

// Example Usage (Illustrative - replace with your actual logic)
// This section is for demonstration and should be adjusted to match your application.
/*
// Assuming $db is your established PDO database connection
// Example:
// $db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

$email = 'testuser@example.com';
$newPassword = 'NewPassword123';  // Replace with a secure password

if (forgotPassword($email, $newPassword, $db)) {
    echo "Password reset successful for $email.";
} else {
    echo "Password reset failed for $email.";
}
*/


?>
