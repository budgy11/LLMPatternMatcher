    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * This function generates a secure, unique password reset token and sends it to
 * the user's email address.
 *
 * @param string $email The email address of the user.
 * @param string $tokenPrefix A prefix for the token to ensure uniqueness.
 * @param string $fromEmail The email address from which the reset email will be sent.
 * @param string $resetLinkBaseUrl The base URL where the reset link will be generated (e.g., 'https://example.com/reset-password').
 * @return bool True on success, false on failure (e.g., email sending issue).
 */
function forgotPassword(string $email, string $tokenPrefix = 'reset_', string $fromEmail = 'noreply@example.com', string $resetLinkBaseUrl = 'https://example.com/reset-password'): bool
{
    // 1. Generate a unique token
    $token = $tokenPrefix . bin2hex(random_bytes(32)); // Use bin2hex for proper hex encoding

    // 2. Store the token in the database (using a secure method - this is simplified for demonstration)
    // Replace this with your actual database interaction
    $isValid = saveTokenToDatabase($email, $token);

    if (!$isValid) {
        return false;
    }

    // 3. Create the reset link
    $resetLink = $resetLinkBaseUrl . '?token=' . urlencode($token); // urlencode for safe URL formatting

    // 4. Send the reset email
    $subject = 'Password Reset Request';
    $message = "Please click on the following link to reset your password: " . $resetLink;
    $headers = "From: " . $fromEmail . "\r
";
    $headers .= "Reply-To: " . $fromEmail . "\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
"; // Crucial for HTML emails

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        // Handle email sending failure (e.g., log an error)
        error_log("Failed to send password reset email to " . $email);
        return false;
    }
}


/**
 * Placeholder function to simulate saving the token in the database.
 * Replace this with your actual database interaction.
 *
 * @param string $email The email address.
 * @param string $token The generated token.
 * @return bool True if the token was saved, false otherwise.
 */
function saveTokenToDatabase(string $email, string $token): bool
{
    // Simulate saving to a database (replace with your actual database code)
    // This is a very simple example and should be replaced with robust database interaction.

    // For demonstration:
    $tokens = ['john.doe@example.com' => $token, 'jane.doe@example.com' => $token];

    if (isset($tokens[$email])) {
        return true;
    } else {
        return false;
    }
}


// Example Usage:
$userEmail = 'john.doe@example.com';

if (forgotPassword($userEmail)) {
    echo "Password reset email sent to " . $userEmail . "
";
} else {
    echo "Failed to send password reset email to " . $userEmail . "
";
}


// Important Considerations & Security Best Practices:

// 1. Token Expiration:
//   - Always implement token expiration.  Set a reasonable expiration time (e.g., 1 hour) and invalidate the token after use.

// 2. Database Security:
//   - Use a strong password for your database.
//   - Employ prepared statements or parameterized queries to prevent SQL injection vulnerabilities.
//   - Consider using an ORM (Object-Relational Mapper) to simplify database interactions and enhance security.

// 3.  Secure Token Generation:
//    - Use `random_bytes()` for generating truly random tokens.  Avoid predictable or sequential token generation.
//    -  `bin2hex()` is important for proper hexadecimal encoding of the random bytes.

// 4.  URL Encoding:
//    - Always URL-encode the token in the reset link to prevent potential security issues. `urlencode()` handles this.

// 5.  Email Sending Security:
//   - Use a reliable email sending service or library to ensure deliverability and prevent spam issues.
//   - Avoid sending emails directly from your web server, as it can be a security risk.

// 6.  Logging and Monitoring:
//    - Log all password reset requests, including the email address, token, and timestamp.  Monitor these logs for suspicious activity.

// 7.  Rate Limiting:
//     - Implement rate limiting to prevent brute-force attacks on the password reset functionality.

// 8.  Sanitization and Validation:
//    - Sanitize and validate all user input to prevent cross-site scripting (XSS) and other vulnerabilities.


<?php

/**
 * Forgets a user's password.  This is a simplified example and
 * should be adapted to a real-world application with robust security measures.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.  **IMPORTANT:  This example
 *                              doesn't securely hash the password.
 *                              You MUST use password_hash() in a production environment.**
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword)
{
    // 1. Validate Email and Password (Basic - Expand this for real use)
    if (empty($email) || empty($newPassword)) {
        return false;
    }

    // 2. Check if User Exists (Ideally use database query)
    // In a real app, you would query your database to confirm the email exists.
    // This is a placeholder for demonstration purposes.

    $user_exists = false; // Simulate user existing

    if($user_exists){
        // 3. Generate a Unique Token (IMPORTANT!)
        $token = bin2hex(random_bytes(32));

        // 4. Store the Token and User ID (In a real app, use a database)
        //  You'd typically store this in a table or a separate file.
        $userId = 123; // Placeholder user ID (replace with actual user ID)

        // 5. Create the Reset Token Data (Store this securely)
        $resetData = [
            'user_id' => $userId,
            'token' => $token,
            'expiry' => time() + 3600  // Token expires in 1 hour
        ];

        // 6.  Store the Reset Token Data - For Example, to a file.
        //  In a real app, use a database.  This is just a placeholder.
        file_put_contents('reset_tokens.txt', json_encode($resetData));  

        // 7. Send the Password Reset Email (Implement this)
        $subject = 'Password Reset';
        $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a> to reset your password."; // Ensure proper URL encoding
        $headers = "From: your_email@example.com" . "\r
";

        mail($email, $message, $headers);

        return true; // Password reset link sent successfully
    } else {
        return false; // User not found
    }
}

// **Example Usage (for testing)**
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["newPassword"];

    if (forgotPassword($email, $newPassword)) {
        echo "Password reset link sent to your email.";
    } else {
        echo "Invalid email or password.";
    }
}
?>
