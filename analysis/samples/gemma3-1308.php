        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <button type="submit">Request Password Reset</button>
    </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 * 
 * This function generates a unique, time-based token and sends an email to the user
 * with a link to reset their password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your website.  Used to construct the reset link.
 * @return bool True on success, false on failure (e.g., email not sent).
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 2. Store the token in the database (replace with your actual database code)
    //  -  This is a simplified example; use prepared statements for security.
    //  -  Ensure you have a table to store the token and user ID.
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); 

    try {
        $stmt = $db->prepare("INSERT INTO password_resets (user_email, token, expires_at) VALUES (:email, :token, :expires_at)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $expires_at = time() + 3600; // Token expires in 1 hour
        $stmt->bindParam(':expires_at', $expires_at);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log for debugging
        return false;
    }


    // 3. Construct the reset link
    $resetLink = $baseUrl . "/reset_password.php?token=" . $token;

    // 4. Send the email
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on the following link: " . $resetLink;
    $headers = 'From: your_email@example.com' . "\r
"; // Replace with your email address

    if (mail($to, $subject, $message, $headers)) {
        // Email sent successfully
        return true;
    } else {
        // Email sending failed
        // Handle email sending errors (e.g., SMTP issues)
        error_log("Failed to send email to " . $email);
        return false;
    }
}

// Example Usage (This part is just for demonstration)
// You'll need to integrate this function into your existing application.

/*
$email = 'testuser@example.com';  // Replace with the user's email
$baseUrl = 'http://yourwebsite.com'; // Replace with your website URL

if (forgotPassword($email, $baseUrl)) {
    echo "Password reset email sent to " . $email;
} else {
    echo "Failed to send password reset email.";
}
*/

?>
