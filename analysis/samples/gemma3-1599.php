    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Check if the email exists
    $user = getUserByEmail($email);

    if (!$user) {
        return false; // User not found
    }

    // 2. Generate a unique token and store it
    $token = generateUniqueToken();
    $hashedToken = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

    // 3. Store the token in the database (associate it with the user)
    $result = updateUserToken($user, $hashedToken);

    if (!$result) {
        return false; // Failed to update user's token
    }

    // 4. Send the password reset email
    if (!sendResetPasswordEmail($user->email, $token)) {
        // If sending email fails, consider deleting the token to avoid security issues
        deleteUserToken($user, $hashedToken);
        return false;
    }

    return true;
}

/**
 * Helper function to get a user by email.  Replace with your database query.
 *
 * @param string $email The email address to search for.
 * @return User|null  The user object if found, or null if not found.
 */
function getUserByEmail(string $email): ?User
{
    //  This is a placeholder.  Replace with your actual database query.
    //  Example:
    //  $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    //  $stmt->execute([$email]);
    //  $user = $stmt->fetch(\PDO::FETCH_ASSOC); // Or whatever fetch method you use
    //  return $user ? new User($user) : null;

    // Dummy user object for testing
    return new User(['id' => 1, 'email' => $email]);
}


/**
 * Generate a unique token.  Use a more robust method in production.
 *
 * @return string  A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // 32 bytes = 256 bits
}


/**
 *  This is a placeholder - implement your database update logic here.
 *
 * @param User $user The user object to update.
 * @param string $hashedToken The hashed token.
 * @return bool True if update was successful, false otherwise.
 */
function updateUserToken(User $user, string $hashedToken): bool
{
    // Replace with your actual database update query
    // Example:
    // $stmt = $db->prepare("UPDATE users SET token = ? WHERE id = ?");
    // $stmt->execute([$hashedToken, $user->id]);
    // return $stmt->rowCount() > 0;

    //Dummy success for testing
    return true;
}

/**
 * Delete the token from the database for a user.
 * @param User $user
 * @param string $hashedToken
 */
function deleteUserToken(User $user, string $hashedToken)
{
    // Replace with your actual database delete query
    // Example:
    // $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND token = ?");
    // $stmt->execute([$user->id, $hashedToken]);
}



/**
 * Send a password reset email.
 *
 * @param string $email The email address to send the email to.
 * @param string $token The unique token.
 * @return bool True if email sent successfully, false otherwise.
 */
function sendResetPasswordEmail(string $email, string $token): bool
{
    // Replace with your email sending logic (e.g., using PHPMailer or similar library)
    // This is a placeholder.  In a real application, you'd send an email.

    // Example (simulated email sending):
    //  $subject = 'Password Reset Request';
    //  $body = "Please use the following token to reset your password: " . $token;
    //  $headers = ['MIME-Version: 1.0', 'Content-type: text/html; charset=UTF-8'];
    //  return mail($email, $body, $headers);

    //Dummy success for testing
    return true;
}



// Example User class - adapt to your actual User model
class User {
    public int $id;
    public string $email;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
    }
}


// Example Usage (Demonstration - replace with actual input)
$email = 'testuser@example.com';

if (forgotPassword($email)) {
    echo "Password reset email sent successfully to $email";
} else {
    echo "Failed to reset password for $email";
}

?>
