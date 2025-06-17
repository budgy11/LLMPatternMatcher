    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email">
    <button type="submit" name="reset_password_request">Request Password Reset</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Validate email format (Basic check, consider more robust validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided.");
        return false;
    }

    // 2. Check if the user exists in the database.
    $user = db_get_user_by_email($email); // Replace with your actual database query
    if (!$user) {
        error_log("User not found with email: " . $email);
        return false;
    }

    // 3. Generate a unique token and store it in the database (associating with the user).
    $token = generate_unique_token();
    $result = db_set_password_reset_token($user['id'], $token); // Replace with your database query
    if (!$result) {
        error_log("Failed to set password reset token for user: " . $email);
        return false;
    }

    // 4.  Send an email with the reset link.
    $reset_link = generate_reset_link($token);
    send_password_reset_email($user['email'], $reset_link);

    return true;
}


/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32));  // Or use a more robust random string generator.
}

/**
 * Generates a reset link (URL) using the token.
 *
 * @param string $token The reset token.
 * @return string The reset link.
 */
function generate_reset_link(string $token): string
{
    return "https://yourdomain.com/reset_password?token=" . urlencode($token);
}


/**
 * Placeholder for the actual email sending function.  Replace with your email sending implementation.
 * @param string $email The recipient's email address.
 * @param string $link The password reset link.
 */
function send_password_reset_email(string $email, string $link): void
{
    // Replace this with your email sending code
    // Example (using a hypothetical function):
    // send_email($email, "Password Reset", $link);
    error_log("Password reset email sent to: " . $email . " with link: " . $link);
}



// ---  Database Helper Functions (Placeholders - Implement your own) ---

/**
 * Retrieves a user's information from the database based on their email address.
 * @param string $email
 * @return array|null An array containing user data, or null if not found.
 */
function db_get_user_by_email(string $email): ?array
{
    // Replace this with your database query to retrieve user by email.
    // This is just a placeholder.
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


/**
 * Sets a password reset token in the database for a given user.
 * @param int $userId The ID of the user.
 * @param string $token The password reset token.
 * @return bool True on success, false on failure.
 */
function db_set_password_reset_token(int $userId, string $token): bool
{
    // Replace this with your database query to set the password reset token.
    // Example:
    // $sql = "UPDATE users SET password_reset_token = '$token' WHERE id = $userId";
    // $result = mysqli_query($db, $sql);
    // return $result;

    // Placeholder - always returns true for this example.
    return true;
}

// ---  Example Usage (for testing)  ---

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = $_POST['email'];
//     if (isset($email) && !empty($email)) {
//         if (forgot_password($email)) {
//             echo "Password reset email has been sent to " . $email;
//         } else {
//             echo "Failed to reset password. Please try again.";
//         }
//     } else {
//         echo "Please enter your email address.";
//     }
// }
?>
