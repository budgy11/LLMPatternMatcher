

<?php

// Assuming you have a database connection established as $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting the password reset.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (Basic)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);

    if ($user === false) {
        error_log("User with email " . $email . " not found."); // Log for debugging
        return false;
    }

    // 3. Generate a unique token
    $token = generateUniqueToken();

    // 4. Store the token and user ID in the database
    $result = saveTokenToDatabase($user['id'], $token);

    if ($result === false) {
        error_log("Failed to save token to database.");
        return false;
    }

    // 5. Send an email with the reset link
    sendResetPasswordEmail($user['email'], $token);

    return true;
}

/**
 * Helper function to get user by email
 * @param string $email
 * @return array|bool User object or false if user not found
 */
function getUserByEmail(string $email): array|bool
{
    // Replace this with your actual database query
    // This is just a placeholder for demonstration
    $users = [
        ['id' => 1, 'email' => 'user1@example.com', 'password' => 'hashed_password1'],
        ['id' => 2, 'email' => 'user2@example.com', 'password' => 'hashed_password2']
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return false;
}

/**
 * Helper function to generate a unique token
 * @return string
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number
}

/**
 * Helper function to save the token to the database
 * @param int $userId
 * @param string $token
 * @return bool
 */
function saveTokenToDatabase(int $userId, string $token): bool
{
    // Replace this with your actual database query
    // This is just a placeholder for demonstration
    // Example using MySQLi (adjust for your database)
    $db = new mysqli('localhost', 'username', 'password', 'database_name');
    if ($db->connect_error) {
        error_log("Failed to connect to database: " . $db->connect_error);
        return false;
    }

    $sql = "INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("is", $userId, $token);
    $result = $stmt->execute();

    $stmt->close();
    $db->close();
    return $result;
}


/**
 * Helper function to send a password reset email
 * @param string $email
 * @param string $token
 */
function sendResetPasswordEmail(string $email, string $token)
{
    // Replace this with your email sending logic (e.g., using PHPMailer)
    // This is just a placeholder for demonstration
    $subject = 'Password Reset Request';
    $message = "Click this link to reset your password: http://yourwebsite.com/reset-password?token=$token"; // Replace with your actual reset link URL

    // Send the email here (using PHPmailer or similar)
    // Example using simple echo for demonstration
    error_log("Email sent to: " . $email . ", Subject: " . $subject . ", Link: " . $message);
}


// Example Usage (for testing - don't use this directly in your application)
//  Be careful, this is just for demo purposes.  Never use this in a production environment.
//  It's important to secure your application properly.
$emailToReset = 'user1@example.com'; // Replace with the actual email you want to test
if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . "<br>";
} else {
    echo "Failed to send password reset email to " . $emailToReset . "<br>";
}

?>
