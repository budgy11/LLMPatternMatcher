

<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param int $userId The user's ID.
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, int $userId) {

    // 1. Check if user exists
    $user = getUserById($userId);  // Implement this function to retrieve the user by ID
    if (!$user) {
        error_log("User with ID {$userId} not found."); // Log for debugging
        return false;
    }

    // 2. Hash the new password - Important for security!
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 3. Update the user's password in the database
    $sql = "UPDATE users SET password = '$hashedPassword' WHERE id = {$userId}";  // Adjust 'users' table and column names if needed
    $result = mysqli_query($GLOBALS['db'], $sql); // Assuming you're using MySQLi

    if (!$result) {
        error_log("Error updating password for user ID {$userId}: " . mysqli_error($GLOBALS['db'])); // Log for debugging
        return false;
    }


    // 4. Send password reset email
    $resetLink = generateResetLink($userId); // Implement this function (see example below)
    $subject = "Password Reset Request";
    $body = "To reset your password, please click on the following link: " . $resetLink;
    $headers = "From: your-website@example.com\r
";
    if (sendEmail($email, $subject, $body, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to {$email}");
        return false;
    }
}


/**
 * Example implementation for generateResetLink (you'll need to customize this)
 */
function generateResetLink(int $userId): string {
    // Generate a unique token (e.g., using UUID)
    $token = bin2hex(random_bytes(32));

    // Store the token in the database linked to the user ID
    // (This is a simplified example - proper security requires careful handling of tokens)
    //  In a real application, you'd probably use a more robust mechanism.
    saveToken($userId, $token);

    // Construct the reset link
    return "https://yourwebsite.com/reset-password?token=" . urlencode($token);
}


/**
 * Example implementation for saveToken (customize for your database)
 */
function saveToken(int $userId, string $token): void {
    // This is a placeholder -  adapt this to your database setup
    //  Store the token in the database associated with the user.
    //  For example, you might have a 'tokens' table with columns 'user_id' and 'token'.
    //  Important: Use prepared statements to prevent SQL injection.

    $sql = "INSERT INTO tokens (user_id, token) VALUES (?, ?)";
    $stmt = mysqli_prepare($GLOBALS['db'], $sql); // Assuming MySQLi
    mysqli_stmt_bind_param($stmt, "is", $userId, $token);  // 'i' for integer, 's' for string
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}



/**
 * Example implementation for sendEmail (you'll need to configure your email server)
 */
function sendEmail(string $to, string $subject, string $body, string $headers) {
    //  Replace with your email sending logic (e.g., using PHPMailer, SwiftMailer, or a direct SMTP connection)
    //  This is a placeholder -  you *must* implement this correctly.

    // Example using a dummy email function:
    error_log("Simulating email sending to {$to} with subject: {$subject}");  //  For debugging
    return true; //  Return true to indicate success (but the email isn't actually sent)
}



/**
 * Placeholder function to get a user by ID
 */
function getUserById(int $userId): ?object {
    //  Implement this function to retrieve the user from your database
    //  Example (adapt to your database schema):
    $sql = "SELECT * FROM users WHERE id = {$userId}";
    $result = mysqli_query($GLOBALS['db'], $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }

    return null;
}

?>
