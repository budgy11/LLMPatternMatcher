
    <form action="forgot_password.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your email" required>
        <br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br><br>

        <button type="submit">Reset Password</button>
    </form>

    <br>
    <a href="login.php">Back to Login</a>


</body>
</html>


<?php

// Assume you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
    // 1. Validate Email (Important for security)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided.");
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);
    if (!$user) {
        error_log("User with email {$email} not found.");
        return false;
    }

    // 3. Generate a unique reset token
    $resetToken = generateUniqueToken();

    // 4. Store the token in the database, associated with the user
    $result = storeResetToken($user->id, $resetToken);
    if (!$result) {
        error_log("Failed to store reset token for user {$email}.");
        return false;
    }

    // 5. Build the reset link
    $resetLink = generateResetLink($user->email, $resetToken);

    // 6. Send the reset link via email
    if (!sendResetEmail($user->email, $resetLink)) {
        // If sending email fails, you might want to log it for debugging or
        // consider alternative notification methods.
        error_log("Failed to send reset email to {$email}.");
    }


    return true;
}

// Helper functions (implement these based on your database and email setup)

/**
 * Retrieves a user from the database based on their email.
 *
 * @param string $email The email address to search for.
 * @return User|null  A User object if found, null otherwise.
 */
function getUserByEmail(string $email): ?User {
    //  Replace this with your actual database query
    // Example using a fictional User class:
    $db = getDatabaseConnection(); // Get your database connection
    $query = "SELECT * FROM users WHERE email = '{$email}'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = new User($result->fetch_assoc());
        return $user;
    }

    return null;
}

/**
 * Generates a unique random token.  Important for security.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); // Secure random bytes
}


/**
 * Stores the reset token in the database, associated with the user's ID.
 *
 * @param int $userId The ID of the user.
 * @param string $token The reset token.
 * @return bool True if the token was stored successfully, false otherwise.
 */
function storeResetToken(int $userId, string $token): bool {
    // Replace this with your actual database operation.  Consider using prepared statements.
    // Example using a fictional User class:
    $db = getDatabaseConnection();
    $query = "UPDATE users SET reset_token = '{$token}' WHERE id = {$userId}";
    return $db->query($query) === true;
}



/**
 * Generates the reset link.
 *
 * @param string $email The user's email address.
 * @param string $token The reset token.
 * @return string The reset link.
 */
function generateResetLink(string $email, string $token): string {
    return "https://yourwebsite.com/reset-password?token={$token}&email={$email}"; // Replace with your actual URL
}



/**
 * Sends the reset email.
 *
 * @param string $email The user's email address.
 * @param string $resetLink The reset link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendResetEmail(string $email, string $resetLink): bool {
    // Replace this with your actual email sending logic.
    //  Use a secure email sending service.

    // This is just a placeholder - implement your email sending code here.
    // Example:
    // $subject = "Password Reset Link";
    // $message = "Click the link below to reset your password: " . $resetLink;
    // $headers = "From: yourname@example.com\r
";
    // return mail($email, $message, $headers);

    error_log("Email sending to {$email} with link {$resetLink} is not yet implemented.");
    return false;
}


// ---- Example Usage (for testing) ----
//  This is just for demonstration.  Do not use this in a production environment
//  without proper security considerations.

// $email = "testuser@example.com"; // Replace with a valid email address
// if (forgotPassword($email)) {
//     echo "Reset link sent to {$email}. Check your email!";
// } else {
//     echo "Failed to send reset link.";
// }

?>
