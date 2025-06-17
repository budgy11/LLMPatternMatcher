<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset</h1>

    <form method="post" action="reset_password.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token, sends an email with a reset link,
 * and flags the user's account as requiring a password reset.
 *
 * @param string $email The user's email address.
 * @param string $token A unique token for security.  This should be generated
 *                      using a cryptographically secure random number generator
 *                      (e.g., `random_bytes` or `random_int`).
 * @param string $reset_link_expiry  The expiry time for the reset link in seconds.
 *                                     Defaults to 3600 seconds (1 hour).
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token, string $reset_link_expiry = 3600)
{
    // 1. Validate Email (Basic check - expand this for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log the error (for debugging)
        return false;
    }

    // 2. Check if user exists
    $user = getUserById($email); // Assuming you have a function getUserById()
    if (!$user) {
        error_log("User with email {$email} not found.");
        return false;
    }

    // 3. Generate a Unique Token
    $token = generate_unique_token(); // Assuming you have a function generate_unique_token()

    // 4.  Store the token and expiry in the database.
    //  This is a placeholder - you MUST implement this.  Do *not* just
    //  hardcode this.
    $result = store_token($user->id, $token, $reset_link_expiry);
    if (!$result) {
        error_log("Failed to store token for user {$email}.");
        return false;
    }


    // 5. Create the reset link
    $reset_link = "/reset_password?token={$token}&expiry={$reset_link_expiry}";

    // 6. Send the reset email
    if (!send_reset_password_email($user->email, $reset_link)) {
        error_log("Failed to send reset password email to {$user->email}.");
        // Optionally, you might want to delete the stored token here if email sending fails.
        // delete_token($user->id, $token);
        return false;
    }

    return true;
}


/**
 * Placeholder functions - Implement these based on your specific database setup.
 */

/**
 * Retrieves a user by their email address.
 *
 * @param string $email The user's email address.
 * @return User|null The User object if found, null otherwise.
 */
function getUserById(string $email): ?User {
    // This is a placeholder - implement your database query here.
    //  Example (using a hypothetical User class):
    //  $query = "SELECT * FROM users WHERE email = ?";
    //  $stmt = $db->prepare($query);
    //  $stmt->bind_param("s", $email);
    //  $stmt->execute();
    //  $result = $stmt->get_result();
    //  if ($result->num_rows > 0) {
    //      $row = $result->fetch_assoc();
    //      return new User($row['id'], $row['email'], $row['password'], ...);
    //  }
    //  return null;

    return null; // Placeholder
}


/**
 * Generates a unique, cryptographically secure token.
 *
 * @return string The unique token.
 */
function generate_unique_token(): string
{
    return random_bytes(32); // Generates a 32-byte (256-bit) random string.
    //  Alternatively, you could use random_int(32) if random_bytes is unavailable.
}

/**
 * Stores the token and expiry in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token.
 * @param int $expiry The expiry time in seconds.
 * @return bool True on success, false on failure.
 */
function store_token(int $userId, string $token, int $expiry)
{
    // This is a placeholder - implement your database query here.
    //  Example:
    //  $query = "INSERT INTO password_tokens (user_id, token, expiry) VALUES (?, ?, ?)";
    //  $stmt = $db->prepare($query);
    //  $stmt->bind_param("iii", $userId, $token, $expiry);
    //  $result = $stmt->execute();
    //  return $result;

    return true; // Placeholder
}

/**
 * Deletes the token from the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token.
 * @return bool True on success, false on failure.
 */
function delete_token(int $userId, string $token)
{
    // This is a placeholder - implement your database query here.
    //  Example:
    //  $query = "DELETE FROM password_tokens WHERE user_id = ? AND token = ?";
    //  $stmt = $db->prepare($query);
    //  $stmt->bind_param("is", $userId, $token);
    //  $result = $stmt->execute();
    //  return $result;
    return true; // Placeholder
}



/**
 * Sends the reset password email.
 *
 * @param string $to The recipient's email address.
 * @param string $reset_link The reset password link.
 */
function send_reset_password_email(string $to, string $reset_link)
{
    // This is a placeholder - implement your email sending logic here.
    //  Example (using a hypothetical email library):
    //  $subject = "Password Reset";
    //  $message = "Click the link below to reset your password: " . $reset_link;
    //  $headers = "From: your-store@example.com\r
";
    //  $result = mail($to, $message, $headers);
    //  return $result === true;

    return true; // Placeholder
}


// Example usage (for testing - NEVER include this in production code)
// $email = "test@example.com";
// $token = generate_unique_token();
// if (forgot_password($email, $token)) {
//     echo "Reset password link sent to {$email}
";
// } else {
//     echo "Failed to generate reset password link.
";
// }
?>
