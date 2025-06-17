

<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with an 'email' column.

/**
 * Resets a user's password via email.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Important for security)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);

    if ($user === false) {
        error_log("User with email " . $email . " not found.");
        return false; // User doesn't exist
    }

    // 3. Generate a unique token
    $token = generate_unique_token();

    // 4. Hash the token for security
    $hashed_token = hash('sha256', $token);  // Using SHA256 for strong hashing

    // 5. Update the user's record with the token
    if (!update_user_token($user['id'], $hashed_token)) {
        error_log("Failed to update user token for email " . $email);
        return false;
    }

    // 6. Send the password reset email
    if (!send_password_reset_email($user['email'], $token)) {
        error_log("Failed to send password reset email to " . $email);
        // Optionally:  You might want to try updating the token back to 'null'
        // to clear it if the email sending failed.
        //  This is a more robust approach.
        //  remove_user_token($user['id']);
        return false;
    }

    return true;
}



/**
 * Placeholder function to retrieve a user by email.  Replace with your database query.
 *
 * @param string $email The email address to search for.
 * @return array|bool An array representing the user data, or false if not found.
 */
function get_user_by_email(string $email): ?array {
    // Replace this with your actual database query.  This is just a placeholder.
    // Example:
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //     return mysqli_fetch_assoc($result);
    // }
    // return false;

    // Dummy data for testing purposes
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'securepass']
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }

    return false;
}



/**
 * Placeholder function to generate a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
    return bin2hex(random_bytes(32));  // Use random_bytes for strong randomness
}


/**
 * Placeholder function to hash a token.
 *
 * @param string $token The token to hash.
 * @return string The hashed token.
 */
function hash_token(string $token): string {
    return hash('sha256', $token);
}

/**
 * Placeholder function to update a user's token.  Replace with your database query.
 *
 * @param int $userId The ID of the user to update.
 * @param string $hashedToken The hashed token to store.
 * @return bool True if the update was successful, false otherwise.
 */
function update_user_token(int $userId, string $hashedToken): bool {
    // Replace this with your actual database query. Example:
    // mysqli_query($db, "UPDATE users SET token = '$hashedToken' WHERE id = $userId");
    return true; // Always return true for now - replace with actual success check
}

/**
 * Placeholder function to remove a user's token (for cleanup).
 *
 * @param int $userId The ID of the user to remove the token for.
 * @return bool True if successful, false otherwise.
 */
function remove_user_token(int $userId): bool{
    // Replace this with your database query.
    return true; //  Replace with actual success check
}


/**
 * Placeholder function to send a password reset email.
 *
 * @param string $email The recipient's email address.
 * @param string $token The password reset token.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_password_reset_email(string $email, string $token): bool {
    // This is just a placeholder -  replace with your email sending logic.
    //  e.g., using PHPMailer or similar.
    // Example (placeholder):
    //  mail($email, "Password Reset", "Click this link to reset your password:  http://example.com/reset?token=$token");
    // Return true for now - replace with actual success check
    error_log("Password reset email sent to " . $email . " with token " . $token);
    return true;
}


// Example Usage (for testing)
// If you call this function, it will log errors and return true
//  You must replace the placeholder functions with your actual database implementation.
$reset_result = forgot_password('test@example.com');

if ($reset_result) {
    echo "Password reset process initiated successfully.  A reset email has been sent (placeholder - verify email!).
";
} else {
    echo "Password reset process failed. Check error logs.
";
}
?>
