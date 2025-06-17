

<?php

/**
 * Forgot Password Function
 * 
 * This function generates a temporary password and sends it to the user's email.
 * 
 * @param string $email      The email address of the user.
 * @param string $resetLinkPrefix The prefix for the reset link (e.g., "https://example.com/reset-password/")
 * @param string $tokenLifetimeSeconds  The amount of time (in seconds) the reset token is valid.
 * @param string $fromEmail   The email address to send the reset password link from.
 * 
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $resetLinkPrefix, int $tokenLifetimeSeconds, string $fromEmail)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log the error for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a strong, random token

    // 3. Hash the Token (For Security)
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the Token in the Database (Replace with your database logic)
    // IMPORTANT: This is a placeholder. You MUST implement this logic
    // using your database connection and table structure.
    $user_id = getUserIDFromEmail($email); // Function to get user ID from email - replace with your implementation
    if ($user_id === null) {
        error_log("User not found for email: " . $email);
        return false;
    }

    // Store the token and user ID.  Replace this with your DB query.
    // In a real application, you would likely use prepared statements 
    // to prevent SQL injection vulnerabilities.
    $success = storeToken($user_id, $token);  // Function to store the token - replace with your implementation
    if (!$success) {
        error_log("Failed to store token for user: " . $email);
        return false;
    }

    // 5. Create the Reset Link
    $resetLink = $resetLinkPrefix . "?" . http_build_query(['token' => $token]);

    // 6. Send the Email
    $subject = "Password Reset Request";
    $message = "To reset your password, please click on the following link: " . $resetLink;
    $headers = "From: " . $fromEmail . "\r
";
    $headers .= "Reply-To: " . $fromEmail . "\r
";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email to: " . $email);
        // Consider removing the token if the email fails to send.
        // This prevents it from being used indefinitely if email delivery is unreliable.
        // removeToken($user_id, $token); // Implement this function
        return false;
    }
}

/**
 * Placeholder functions - Replace with your own implementations
 * These are placeholders for database interaction and token removal.
 */

/**
 * Placeholder function to get user ID from email.  Replace with your database query.
 * @param string $email
 * @return int|null
 */
function getUserIDFromEmail(string $email): ?int
{
    // Replace this with your actual database query to get the user ID.
    // Example:
    // $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   return mysqli_fetch_assoc($result)['id'];
    // }
    // return null;
    return null; // Placeholder
}

/**
 * Placeholder function to store the token in the database.
 * Replace with your database query.
 * @param int $userId
 * @param string $token
 */
function storeToken(int $userId, string $token): bool
{
    // Replace this with your actual database query.
    // Example:
    // mysqli_query($conn, "INSERT INTO reset_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 3600 SECOND)");  // Expires in 1 hour
    return true; // Placeholder
}


/**
 * Placeholder function to remove the token from the database.
 * Replace with your database query.
 * @param int $userId
 * @param string $token
 */
function removeToken(int $userId, string $token): bool
{
    // Replace this with your actual database query.
    // Example:
    // mysqli_query($conn, "DELETE FROM reset_tokens WHERE user_id = $userId AND token = '$token'");
    return true; // Placeholder
}

// Example Usage (Replace with your actual email, prefix, and from email)
// $email = "test@example.com";
// $resetLinkPrefix = "https://yourwebsite.com/reset-password/";
// $tokenLifetimeSeconds = 7200; // 2 hours
// $fromEmail = "noreply@yourwebsite.com";
//
// if (forgotPassword($email, $resetLinkPrefix, $tokenLifetimeSeconds, $fromEmail)) {
//     echo "Password reset email sent!";
// } else {
//     echo "Password reset failed.";
// }

?>
