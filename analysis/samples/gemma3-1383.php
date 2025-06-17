

<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Generates a unique token and saves it to the database.
 *
 * @param string $email The email address to reset the password for.
 * @return string|false The unique token if generated successfully, or false if an error occurs.
 */
function generateResetToken() {
  $token = bin2hex(random_bytes(32)); // Generates a secure, random 32-byte token
  return $token;
}

/**
 * Creates a password reset link.
 *
 * @param string $email The email address to reset the password for.
 * @return string|false The generated password reset link if successful, or false if an error occurs.
 */
function createResetLink(string $email) {
    $token = generateResetToken();

    // Example:  You might save the token to the database, along with the email.
    // This is just a placeholder - you'll need to implement your database saving logic.
    // For demonstration, we'll just return the token string.
    //  Don't just return the token, you *must* store it securely!
    return $token;
}


/**
 * Resets the user's password based on the token.
 *
 * @param string $token The password reset token.
 * @param string $newPassword The new password for the user.
 * @param string $email The user's email address.
 * @return bool True if the password was successfully reset, false otherwise.
 */
function resetPassword(string $token, string $newPassword, string $email) {
    // 1. Verify the Token
    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $db->prepare($query); // Use your database connection
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // 2. Check if the token exists for this user
    $query = "SELECT id FROM reset_tokens WHERE token = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();

    if ($stmt->error) {
        error_log("Database error: " . $stmt->error); // Log the error for debugging.
        $stmt->close();
        return false;
    }

    if ($stmt->error) {
        $stmt->close();
        return false;
    }

    if ($stmt->affected_rows === 0) {
        $stmt->close();
        return false; // Token not found for this user
    }
    
    // 3. Update the user's password
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", password_hash($newPassword, PASSWORD_DEFAULT), $userId);
    $stmt->execute();
    $stmt->close();


    // 4. Delete the token from the database after successful password reset.
    $query = "DELETE FROM reset_tokens WHERE token = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();
    $stmt->close();

    return true;
}


// Example Usage (illustrative only - adapt to your application)
/*
$email = "testuser@example.com";
$newPassword = "NewSecurePassword123";


// Generate a reset link
$resetLink = createResetLink($email);
echo "Reset Link: " . $resetLink . "<br>";


// Reset the password (assuming you've received the $resetLink from a form submission)
if (resetPassword($resetLink, $newPassword, $email)) {
    echo "Password reset successfully!";
} else {
    echo "Password reset failed.";
}
*/


?>
