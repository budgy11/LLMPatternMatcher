

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if an email was sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email (Important Security Step!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);

    if (!$user) {
        error_log("User with email: " . $email . " not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token and Timestamp
    $token = generateUniqueToken();
    $timestamp = time();

    // 4. Create the Reset Token Record (Store this in your database)
    //   *  Email
    //   *  Token
    //   *  Expiration Time
    resetTokenRecord = [
        'email' => $email,
        'token' => $token,
        'expiry' => $timestamp + (60 * 60 * 24) // Expires in 24 hours
    ];

    // Save the record to the database.  Replace this with your actual database query
    if (!saveResetToken($resetTokenRecord)) {
        error_log("Failed to save reset token record for " . $email);
        return false;
    }


    // 5.  Send the Password Reset Email (Implement your email sending logic here)
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: " . base_url() . "/reset-password?token=" . $token; // Replace base_url()

    $headers = "From: " . get_sender_email(); //Replace with your sender email address
    if (!sendEmail($subject, $message, $headers)) {
        error_log("Failed to send password reset email for " . $email);
        //Optionally, you could delete the token from the database if email sending fails
        deleteResetToken($email, $token);
        return false;
    }

    return true;
}

/**
 *  Dummy function to simulate getting user data from database.  Replace with your actual query.
 *  @param string $email
 *  @return array|null User data, or null if not found.
 */
function getUserByEmail(string $email): ?array {
    // Example using a dummy user. Replace with your database query
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Dummy function to generate a unique token.
 * @return string
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); //Generate a 32-byte (256-bit) random string.
}

/**
 * Dummy function to save the reset token record to the database. Replace with your actual database query.
 * @param array $resetTokenRecord
 * @return bool
 */
function saveResetToken(array $resetTokenRecord): bool {
    //Replace with your actual database insert query.  For demonstration, we just return true.
    //Example:
    // $sql = "INSERT INTO reset_tokens (email, token, expiry) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($sql);
    // $stmt->execute([$resetTokenRecord['email'], $resetTokenRecord['token'], $resetTokenRecord['expiry']]);
    return true;
}


/**
 * Dummy function to delete the reset token record. Replace with your actual database query.
 * @param string $email
 * @param string $token
 * @return bool
 */
function deleteResetToken(string $email, string $token): bool {
    //Replace with your actual database delete query.
    //Example:
    //$sql = "DELETE FROM reset_tokens WHERE email = ? AND token = ?";
    //$stmt = $db->prepare($sql);
    //$stmt->execute([$email, $token]);
    return true;
}

/**
 * Dummy function for sending emails.  Replace with your actual email sending logic.
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function sendEmail(string $subject, string $message, string $headers): bool {
    // Simulate email sending.  Replace with your actual email sending logic.
    // You'll likely use a library like PHPMailer or SwiftMailer for actual sending.
    error_log("Simulating sending email: Subject: " . $subject . ", Message: " . $message);
    return true;
}

/**
 * Dummy function to get the base URL.  Replace with your actual base URL.
 * @return string
 */
function base_url(): string {
    // Replace with your application's base URL
    return "http://localhost/my_app/";
}

/**
 * Dummy function to get the sender email.  Replace with your actual sender email.
 * @return string
 */
function get_sender_email(): string {
    // Replace with your actual sender email address
    return "noreply@example.com";
}

// Example Usage:
$email = "test@example.com"; // Replace with the user's email
if (forgotPassword($email)) {
    echo "Password reset email has been sent to " . $email;
} else {
    echo "Failed to send password reset email for " . $email;
}

?>
