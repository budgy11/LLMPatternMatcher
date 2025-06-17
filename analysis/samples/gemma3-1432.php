

<?php

// Assuming you have a database connection established
// Example: $db = new PDO('mysql:host=localhost;dbname=your_database');

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param PDO $db  The database connection.
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, PDO $db) {
    // 1. Verify Email Exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // User not found
    }

    // 2. Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 3. Update the user's password
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedNewPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();


    return true; // Password reset successful
}


// Example Usage (Demonstration)
// **IMPORTANT:  This is just a demonstration and should not be used directly in production code.**
//  Properly handle errors, input validation, and security best practices.

//  For a real application, you would get the newPassword from a form submission.

// Example 1: Successful Reset
// $db = new PDO('mysql:host=localhost;dbname=your_database'); // Replace with your credentials
// $email = 'testuser@example.com';
// $newPassword = 'NewPassword123!';

// if (forgotPassword($email, $newPassword, $db)) {
//     echo "Password reset successful for " . $email . "
";
// } else {
//     echo "Password reset failed for " . $email . "
";
// }


// Example 2:  Demonstrating failure (user doesn't exist)
// $db = new PDO('mysql:host=localhost;dbname=your_database');
// $email = 'nonexistentuser@example.com';
// $newPassword = 'SomePassword';

// if (forgotPassword($email, $newPassword, $db)) {
//     echo "Password reset successful for " . $email . "
";
// } else {
//     echo "Password reset failed for " . $email . "
";
// }


?>
