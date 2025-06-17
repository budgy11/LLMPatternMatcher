    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>

</body>
</html>


<?php

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The email address associated with the user account.
 * @param string $reset_token A unique, randomly generated token.  This is crucial for security!
 * @param string $db_connection A connection object to your database.  This is how you'll interact with your database to update the password.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $reset_token, $db_connection): bool
{
    // **IMPORTANT SECURITY NOTES:**

    // 1.  **Token Validation:**  This function *doesn't* fully validate the $reset_token.
    //     YOU MUST add robust token validation on the frontend (e.g., preventing XSS) *and*
    //     on the backend (e.g., checking against a table or database entry).  The example below is a simplified placeholder.
    //     Never trust user input - especially tokens - without thorough validation.
    // 2.  **Password Complexity:**  This example uses a simple password.  In a real application,
    //     you should enforce strong password policies.
    // 3.  **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.
    // 4.  **Secure Token Generation:** Use a cryptographically secure random number generator (e.g., `random_bytes()` or `openssl_random_pseudo_bytes()`) to generate the token.
    // 5.  **Database Security:**  Ensure your database connection is secure and that your database server is properly configured.
    // 6.  **HTTPOnly and Secure Cookies:** Use the `HTTPOnly` and `Secure` flags when setting cookies.

    // 1.  Check if the email exists in the database.
    $stmt = $db_connection->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user_id = $stmt->fetchColumn();

    if (!$user_id) {
        // Email doesn't exist, so don't update anything
        return false;
    }

    // 2.  Update the password field with a temporary value and the reset token.
    $password_reset_query = "UPDATE users SET password = ?, reset_token = ? WHERE id = ?";
    $stmt = $db_connection->prepare($password_reset_query);
    $stmt->execute([
        'pending',  // A temporary password (e.g., 'pending') is used.
        $reset_token,
        $user_id
    ]);

    if ($stmt->rowCount() === 0) {
        //  Something went wrong with the update.
        return false;
    }


    return true; // Success!
}


// **Example Usage (Illustrative - adapt to your setup):**

// This is just a sample.  Replace with your actual database connection and token generation code.

// 1. Generate a unique reset token (use a proper, cryptographically secure random number generator!)
// $reset_token = bin2hex(random_bytes(32));  // A more secure example

// 2. Call the function
// $success = forgot_password('user@example.com', $reset_token, $db_connection);

// if ($success) {
//     echo "Password reset email sent to user@example.com.";
// } else {
//     echo "Error resetting password.";
// }
?>
