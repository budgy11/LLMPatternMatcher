    <?php } ?>
    <button type="submit">Request Password Reset</button>
</form>

</body>
</html>


<?php

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $secretKey (Optional) A secret key for verification.  Highly recommended.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $secretKey = "") {
    // 1. Validate Input
    if (empty($email) || empty($newPassword)) {
        return false; // Required fields must be provided
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    if (strlen($newPassword) < 8) { //  Example minimum password length
        return false; //  Minimum length requirement
    }

    // 2.  Check if a user exists
    $user = getUserById($email); //  Assuming you have a function to fetch user by email

    if (!$user) {
        return false; // User not found
    }

    // 3. Verify Secret Key (Important for security)
    if ($secretKey && !verifySecretKey($user->id, $secretKey)) {
        return false; // Invalid secret key
    }

    // 4.  Reset Password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password

    // 5.  Update User's Password
    $result = updateUserPassword($user->id, $hashedNewPassword);

    if (!$result) {
        return false; // Failed to update password
    }

    // 6. Generate and Send Password Reset Email (Optional, but highly recommended)
    // Send an email containing a link to reset the password
    sendPasswordResetEmail($email, $hashedNewPassword); // Assuming you have this function

    return true; // Password reset successful
}


/**
 * Dummy Functions - Replace with your actual implementations
 */
function getUserById(string $email) {
    // Replace this with your database query to fetch the user by email.
    // This is a placeholder.
    $users = [
        ['id' => 1, 'email' => 'test@example.com'],
        ['id' => 2, 'email' => 'another@example.com'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


function verifySecretKey(int $userId, string $secretKey) {
    // In a real implementation, compare the provided secret key
    // to a hash stored for the user.
    // This is just a placeholder.
    // You would typically store the hashed secret key in the database
    // alongside the user's ID.
    // For this example, we'll just return true if the secret key is "secret123".
    return $secretKey === "secret123";
}

function updateUserPassword(int $userId, string $hashedPassword) {
    // Replace this with your database update query to set the password.
    // This is a placeholder.
    // Example:  UPDATE users SET password = '$hashedPassword' WHERE id = $userId;
    echo "Updating password for user ID: " . $userId . " with hashed password: " . $hashedPassword . PHP_EOL;
    return true;
}

function sendPasswordResetEmail(string $email, string $hashedPassword) {
    //  Replace this with your email sending implementation
    //  Example:  Use PHPMailer, SwiftMailer, or another email library
    echo "Sending password reset email to: " . $email . PHP_EOL;
    echo "Hashed password: " . $hashedPassword . PHP_EOL;
}



// --- Example Usage ---
$email = 'test@example.com';
$newPassword = 'MyNewPassword123';
$secretKey = "secret123"; // Include this for verification

if (forgotPassword($email, $newPassword, $secretKey)) {
    echo "Password reset successful for $email" . PHP_EOL;
} else {
    echo "Password reset failed for $email" . PHP_EOL;
}


?>
