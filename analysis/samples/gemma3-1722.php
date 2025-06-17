    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email Address" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * This function generates a random token and sends a password reset email
 * to the user's email address.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your application (e.g., 'https://example.com').  Crucial for generating the reset link.
 * @param string $fromEmail The email address to send the reset email from.
 * @param string $resetLinkExpiryHours The number of hours the reset link will be valid for (default: 1).
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl, string $fromEmail, string $resetLinkExpiryHours = 1)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // More secure than simple random_int
    $expiry = time() + ($resetLinkExpiryHours * 3600);  // Token expires after specified hours

    // 3.  Store Token and User ID in Database (Replace with your actual database logic)
    // In a real application, you'd insert this into a database table.
    // This example uses a simple array for demonstration purposes only.
    $user = get_user_by_email($email); // Replace with your user retrieval function
    if (!$user) {
        error_log("User not found for email: " . $email);
        return false;
    }

    //  Example: Save to a database table called 'password_resets'
    //  CREATE TABLE password_resets (
    //      id INT AUTO_INCREMENT PRIMARY KEY,
    //      user_id INT NOT NULL,
    //      token VARCHAR(255) NOT NULL UNIQUE,
    //      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    //  );

    //  In a real application, you'd insert the $token, $user_id, and $expiry into the database.

    // 4.  Build the Reset Link
    $resetLink = $baseUrl . "/reset_password?token=" . urlencode($token) . "&expiry=" . urlencode($expiry);

    // 5. Send the Password Reset Email
    $subject = "Password Reset";
    $message = "To reset your password, please click the following link: " . $resetLink;
    $headers = "From: " . $fromEmail . "\r
";
    $headers .= "Reply-To: " . $fromEmail . "\r
";

    if (mail($email, $subject, $message, $headers)) {
        // 6.  Success!
        return true;
    } else {
        // 7.  Error Sending Email
        error_log("Failed to send password reset email to " . $email);
        return false;
    }
}


// -------------------  Example Usage (Illustrative - Adapt to your application) -------------------

// IMPORTANT:  Replace these placeholders with your actual settings.
$baseUrl = 'https://example.com/reset_password';
$fromEmail = 'admin@example.com';

// Example: Attempt to reset password for user with email 'testuser@example.com'
$email = 'testuser@example.com';
$success = forgotPassword($email, $baseUrl, $fromEmail);

if ($success) {
    echo "Password reset email has been sent to " . $email;
} else {
    echo "Failed to generate password reset link.";
}


// -------------------  Helper Function (Placeholder - Implement your User Retrieval) -------------------
/**
 * Placeholder function to retrieve a user by email.
 * Replace this with your actual user retrieval logic.
 *
 * @param string $email The email address of the user.
 * @return array|null  The user data as an array, or null if the user is not found.
 */
function get_user_by_email(string $email): ?array {
    // Replace this with your actual database query.
    // This is just a placeholder for demonstration.

    // Example:
    // $user = [];
    // if ($email == 'testuser@example.com') {
    //     $user = ['id' => 1, 'email' => 'testuser@example.com'];
    // }
    return null;  // Or return an empty array if that's what your application expects
}


?>
