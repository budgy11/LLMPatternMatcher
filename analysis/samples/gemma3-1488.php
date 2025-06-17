

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique token and sends an email with a password reset link.
 *
 * @param string $email The email address to send the password reset link to.
 * @param string $baseUrl The base URL of your website.  This is used to construct the reset link.
 * @return bool True on success, false on failure (e.g., email sending failure)
 */
function forgot_password(string $email, string $baseUrl): bool
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32));

    // 2.  Prepare the Reset Link
    $reset_link = $baseUrl . "/reset-password?token=" . urlencode($token);


    // 3.  Store the Token (Using a Database - Example)
    //    This is a simplified example.  In a real application, you'd
    //    use a proper database and consider security best practices.

    // Assume a `users` table with `email` and `password` columns.
    // This is purely illustrative.

    // **IMPORTANT:**  Do *not* store the password in plaintext.
    //  Use password hashing (e.g., bcrypt, Argon2) for secure password storage.

    // Example database connection (replace with your actual connection details)
    // This is just a placeholder.  Error handling is omitted for brevity.
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    try {
        // Check if the user exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // User does not exist.  You might log this for debugging.
            return false;
        }


        //  Update the user's record with the token (use prepared statements!)
        $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token, time() + 3600, $email]); // Expire token after 1 hour
    } catch (PDOException $e) {
        // Handle database errors.  Log them for debugging.
        error_log("Database error: " . $e->getMessage());
        return false;
    }



    // 4. Send the Email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click the link below to reset your password:
" . $reset_link . "

This link will expire in 1 hour.";
    $headers = "From: your_website@example.com\r
"; // Replace with your actual email address

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Handle email sending failure.  Log it for debugging.
        error_log("Email sending failed for user: " . $email);
        return false;
    }
}



/**
 * Example Usage (Illustrative - you'll need to integrate this with your form)
 */
// Simulate a user's email
$user_email = 'testuser@example.com';

if (forgot_password($user_email, 'http://localhost/your_website')) {
    echo "Password reset email sent to " . $user_email;
} else {
    echo "Failed to send password reset email to " . $user_email;
}

?>
