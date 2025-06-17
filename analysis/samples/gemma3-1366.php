

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password via email.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email (Basic - Enhance as needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }


    // 2. Check if User Exists
    $user = db_get_user_by_email($email); // Replace with your database query
    if (!$user) {
        error_log("User with email: " . $email . " not found.");
        return false;
    }

    // 3. Generate a Unique Token
    $token = generate_unique_token();

    // 4. Store Token in Database (Associated with User)
    if (!store_token_in_db($user->id, $token)) {
        error_log("Failed to store token in database for user: " . $email);
        return false;
    }

    // 5. Email the User with Reset Link
    if (!send_reset_password_email($user->email, $token)) {
        // Handle email sending failure - log, retry, or alert admin
        error_log("Failed to send reset password email to: " . $email);
        // You might want to delete the token from the database if email fails.
        delete_token_from_db($user->id);
        return false;
    }

    return true;
}

// ------------------ Helper Functions (Implement these based on your DB and setup) ------------------

/**
 * Retrieves a user from the database based on their email.
 *
 * @param string $email The email address to search for.
 * @return User|null  A User object if found, null otherwise.
 */
function db_get_user_by_email(string $email): ?User {
    // Replace with your actual database query.
    // This is just a placeholder.
    // Example (assuming you have a User class):
    // $query = "SELECT id, email FROM users WHERE email = ?";
    // $stmt = db_query($query, [$email]);
    // if ($stmt->num_rows > 0) {
    //     $user = new User();
    //     $user->id = $stmt->fetch_assoc()['id'];
    //     $user->email = $stmt->fetch_assoc()['email'];
    //     return $user;
    // }
    // return null;

    // Dummy User class for demonstration
    class User {
        public ?int $id = null;
        public string $email = "";
    }
    return null;
}


/**
 * Generates a unique token.  Use a cryptographically secure method.
 * @return string
 */
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}

/**
 * Stores the token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True on success, false on failure.
 */
function store_token_in_db(int $userId, string $token): bool {
    // Replace with your actual database insert query.
    // Example:
    // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES (?, ?, NOW())";
    // $stmt = db_query($query, [$userId, $token]);
    // return $stmt->num_rows > 0;

    // Dummy implementation for demonstration
    if(rand(0, 2) === 0) { // Simulate success 1/3 of the time
        return true;
    }
    return false;
}


/**
 * Deletes the token from the database.
 *
 * @param int $userId The ID of the user.
 * @return bool True on success, false on failure.
 */
function delete_token_from_db(int $userId): bool {
    // Replace with your actual database delete query.
    // Example:
    // $query = "DELETE FROM password_tokens WHERE user_id = ?";
    // $stmt = db_query($query, [$userId]);
    // return $stmt->num_rows > 0;

    // Dummy implementation
    return true;
}


/**
 * Sends the reset password email.
 *
 * @param string $email The email address to send the email to.
 * @param string $token The token to include in the email.
 * @return bool True on success, false on failure.
 */
function send_reset_password_email(string $email, string $token): bool {
    // Implement email sending logic here.
    //  Use a library like PHPMailer for sending emails.

    // This is just a placeholder - in a real implementation, you'd use
    // a mail sending library and properly configure it.

    // Simulate success 1/2 of the time
    if(rand(0, 2) === 0) {
        return true;
    }
    return false;

}



// ------------------ Example Usage (For Testing) ------------------
// $email = "testuser@example.com";  // Replace with an actual email.

// if (forgot_password($email)) {
//     echo "Password reset email sent to " . $email . "
";
// } else {
//     echo "Failed to reset password for " . $email . "
";
// }
?>
