
<form method="post" action="">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" placeholder="Enter your email">
  <button type="submit">Request Password Reset</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgotPassword(string $email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log invalid emails for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email);

    if (!$user) {
        error_log("User with email '$email' not found.");
        return false;
    }

    // 3. Generate a Unique Token (for security)
    $token = generateUniqueToken();

    // 4. Store Token in Database (temporarily) - associate with the user
    $userId = $user['id']; // or however you retrieve the user's ID
    storeToken($userId, $token);


    // 5. Send Password Reset Email
    $resetLink = generateResetLink($token, $user['email']); // Construct the reset link
    sendEmail(['to' => $user['email'], 'subject' => 'Password Reset', 'body' => $resetLink]);

    return true;
}


/**
 * Helper function to get user data by email.  This should be replaced with your actual database query.
 *
 * @param string $email The email address to search for.
 * @return array|null An array containing user data if found, or null if not found.
 */
function getUserByEmail(string $email): ?array {
    // Replace this with your actual database query
    // Example (assuming you have a database connection $db)
    // $result = $db->query("SELECT * FROM users WHERE email = '$email'");
    // $user = $result->fetch_assoc();
    // return $user;

    //Dummy data for demonstration purposes only
    $users = [
        ['id' => 1, 'email' => 'test@example.com'],
        ['id' => 2, 'email' => 'another@example.com'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Generates a unique token (e.g., using a UUID library).
 * This is crucial for security - ensures tokens are not easily guessable.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); // Generates a 32-byte random string and encodes it to hexadecimal.
}



/**
 * Stores the token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 */
function storeToken(int $userId, string $token): void {
    // Replace this with your actual database insert query
    // Example:
    // $db->query("INSERT INTO password_tokens (user_id, token, expiry_date) VALUES ($userId, '$token', NOW() + INTERVAL 24 HOUR)");
}



/**
 * Generates the password reset link.  Includes the token and the user's email
 *
 * @param string $token The token.
 * @param string $email The user's email.
 * @return string The generated link.
 */
function generateResetLink(string $token, string $email): string {
    return "/reset-password?token=" . urlencode($token) . "&email=" . urlencode($email);  //Construct the reset link
}



/**
 *  Sends an email. This function will need to be adapted to your email sending setup (e.g., PHPMailer, SendGrid).
 *
 * @param array $data Array containing 'to', 'subject', and 'body'.
 */
function sendEmail(array $data): void {
    //Replace with your email sending implementation.  This is just a placeholder.
    $to = $data['to'];
    $subject = $data['subject'];
    $body = $data['body'];

    //Dummy email sending
    echo "Sending email to: " . $to . " with subject: " . $subject . "
Body: " . $body . "
";
}



// Example Usage (for testing)
$email = 'test@example.com';

if (forgotPassword($email)) {
    echo "Password reset email sent to $email
";
} else {
    echo "Failed to send password reset email for $email
";
}
?>
