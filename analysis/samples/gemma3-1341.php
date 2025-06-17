        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="token">Token:</label>
        <input type="text" id="token" name="token" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Input
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided."); // Log for debugging
        return false;
    }

    // Sanitize the email address to prevent SQL injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        error_log("Forgot Password: Invalid email format provided.");
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);  // Implement this function (see below)

    if ($user === false) {
        error_log("Forgot Password: User not found with email: " . $email);
        return false;
    }

    // 3. Generate a unique token
    $token = generateUniqueToken();

    // 4. Store the token in the database associated with the user
    //  (This is the crucial part – adapt to your database schema)
    $result = storeToken($user['id'], $token); // Implement this function (see below)

    if (!$result) {
        error_log("Forgot Password: Failed to store token for user: " . $email);
        return false;
    }


    // 5. Send the password reset email
    //  (Implement this function – sendmail, etc.)
    sendPasswordResetEmail($user['email'], $token);


    return true;
}



/**
 * Helper function to get a user by their email.  This is a placeholder.
 *  You must implement this function based on your database structure.
 *
 * @param string $email The email address of the user.
 * @return bool|array The user object if found, false if not found.
 */
function getUserByEmail(string $email): bool|array
{
    // Replace this with your database query.  This is just a dummy example.
    // Assuming a table named 'users' with columns 'id', 'email', 'password', etc.
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->fetch(PDO::FETCH_ASSOC);  // Get the first row as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


/**
 * Helper function to generate a unique token.
 *  This could use a library or a simple random string generator.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Example - generates a 32-byte (256-bit) random string.
}



/**
 * Helper function to store the token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The unique token.
 * @return bool True on success, false on failure.
 */
function storeToken(int $userId, string $token): bool
{
    // Replace with your database code to store the token.
    // Example (using PDO):

    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $expires_at = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', $expires_at);
    return $stmt->execute();
}



/**
 * Helper function to send the password reset email.
 *
 * @param string $email The email address of the user.
 * @param string $token The unique token.
 * @return void
 */
function sendPasswordResetEmail(string $email, string $token)
{
    //  Replace with your email sending logic (e.g., sendmail, SMTP, etc.)
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . "<a href='" . base_url() . "/reset_password?token=" . $token . "'>Reset Password</a>";

    // Example using mail() - may need to configure your server properly
    mail($email, $subject, $message);
}

// Example Usage (For testing - replace with your actual logic)
// if (isset($_POST['email'])) {
//     $email = $_POST['email'];
//     if (forgotPassword($email)) {
//         echo "Password reset email has been sent to " . $email;
//     } else {
//         echo "Failed to process password reset request.";
//     }
// }

?>
