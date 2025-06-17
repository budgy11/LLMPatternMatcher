        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was successfully generated and sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // Validate email format (basic check, use a proper validation library in production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 1. Generate a unique, secure token (using a cryptographically secure random function)
    $token = bin2hex(random_bytes(32));

    // 2. Hash the token (for security)
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);  // Using password_hash for security.

    // 3. Store the token and user ID in the database
    $query = "INSERT INTO password_resets (user_id, token, expires_at)
              VALUES (:user_id, :token, :expires_at)";

    $stmt = $db->prepare($query); // Assuming $db is your database connection
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $hashedToken);
    $stmt->bindParam(':expires_at', time() + (2 * 60 * 60)); // Token expires in 2 hours (example)
    $result = $stmt->execute();

    if (!$result) {
        error_log("Error inserting reset token into database: " . print_r($stmt->errorInfo(), true)); // Log the error
        return false;
    }

    // 4.  Send the password reset link (email)
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
    $headers = "From: Your Website <noreply@yourwebsite.com>"; // Replace with your actual sender email

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email);
        // Optionally, delete the reset token from the database if the email fails.
        // This is crucial to prevent abuse.
        deleteResetToken($userId, $token);
        return false;
    }
}


/**
 * Deletes a password reset token from the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to delete.
 */
function deleteResetToken(int $userId, string $token): void
{
    $query = "DELETE FROM password_resets WHERE user_id = :user_id AND token = :token";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}


// Example Usage (in your web form handling logic)

//  ... (form submission handling) ...

//  $email = $_POST['email']; // Get the email from the form

//  if (forgotPassword($email)) {
//      echo "Password reset email sent. Check your inbox.";
//  } else {
//      echo "An error occurred while sending the password reset email.";
//  }

?>
