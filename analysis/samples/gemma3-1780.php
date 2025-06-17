    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>

    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" required><br><br>

    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

/**
 * Forgets a user's password.
 *
 * This function sends a password reset link to the user's email address.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $reset_token A unique token to be generated and stored.
 * @param $pdo PDO object to interact with the database.  This is crucial.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $reset_token, PDO $pdo) {
    // 1. Sanitize and Validate Input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $reset_token = bin2hex(random_bytes(32)); // Generate a strong, random token (important for security)


    // 3. Store Token in Database
    try {
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_email, reset_token, created_at) VALUES (:email, :token, NOW())");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $reset_token);
        $stmt->execute();
        $reset_id = $pdo->lastInsertId();

        // 4. Send Password Reset Email (Example)
        $subject = "Password Reset Link";
        $headers = "From: your_email@example.com" . "\r
" .
                   "Reply-To: your_email@example.com";
        $message = "Click this link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($reset_token);

        mail($email, $subject, $message, $headers);

        return true; // Indicate success

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());  // Log database errors for debugging
        return false;
    }
}

// Example Usage (Illustrative - Adapt to Your Application)
//  This is NOT the entire application. This is just a demonstration.

// Assuming you have a PDO connection named $pdo
//  and you have a 'password_resets' table with columns:
//   - user_email (VARCHAR)
//   - reset_token (VARCHAR)
//   - created_at (TIMESTAMP)

//  Check if the email exists, but DON'T store the password yet.
//  You'll store the token, not the hashed password.

/*
$email = "testuser@example.com"; // Example email
$reset_token = generate_reset_token(); // Function to generate a random token
if (forgot_password($email, $reset_token, $pdo)) {
    echo "Password reset link has been sent to " . $email . ".";
} else {
    echo "Failed to send password reset link.";
}
*/
?>
