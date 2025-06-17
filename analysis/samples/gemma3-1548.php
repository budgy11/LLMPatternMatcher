    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and available as $db

/**
 * Forgets a user's password and sends a password reset email.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Verify Email Exists
    $user = get_user_by_email($email); // Assuming this function exists and retrieves user by email

    if (!$user) {
        error_log("User with email '$email' not found."); // Log error for debugging
        return false;
    }

    // 2. Generate a Unique Token and Store it
    $token = generate_unique_token(); //  This function generates a random, unique token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

    // 3. Store the token in the database (e.g., in the user's record)
    update_user_token($user->id, $hashed_token);  // Assumes this function updates the token in the database

    // 4. Build the reset link
    $reset_url = "/reset-password?token=$token"; //  Or use a properly sanitized URL

    // 5. Send the Password Reset Email
    $subject = "Password Reset Request";
    $body = "Please click on the following link to reset your password: " . $reset_url;
    $headers = "From: Your Website <contact@yourwebsite.com>"; // Replace with your email
    send_email($email, $subject, $body, $headers);

    return true;
}


/**
 * Placeholder functions -  You need to implement these.
 */

/**
 * Retrieves a user by their email address.  This should query your database.
 *
 * @param string $email The email address to search for.
 * @return User object or null if not found.
 */
function get_user_by_email(string $email): ?User {
    // Replace with your database query logic
    // Example (using a hypothetical User class):
    // $result = db_query("SELECT * FROM users WHERE email = '$email'");
    // if ($result->num_rows > 0) {
    //     return new User($result->fetch_assoc());
    // }
    // return null;
    error_log("get_user_by_email called - need to implement this function");
    return null; // Placeholder
}

/**
 * Generates a unique token.  Consider using a library for cryptographically secure random number generation.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
  // Use a cryptographically secure random number generator for production.
  return bin2hex(random_bytes(32));
}


/**
 *  Updates the user's token in the database.
 *
 * @param int $userId The ID of the user to update.
 * @param string $hashedToken The hashed token.
 */
function update_user_token(int $userId, string $hashedToken): void {
    // Replace with your database update logic.
    // Example:
    // db_query("UPDATE users SET token = '$hashedToken' WHERE id = $userId");
    error_log("update_user_token called - need to implement this function");
}


/**
 * Sends an email.  This should use a mail function or library.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $body The email body.
 * @param string $headers Email headers.
 */
function send_email(string $to, string $subject, string $body, string $headers): void {
    // Replace with your email sending logic.  Use a library like PHPMailer for production.
    error_log("send_email called - need to implement this function");
}



// Example Usage (for testing -  Don't use directly in production)
// To use this function, you'd call it like this:
// $success = forgot_password("testuser@example.com");
// if ($success) {
//   echo "Password reset email sent to testuser@example.com";
// } else {
//   echo "Password reset failed.";
// }


?>
