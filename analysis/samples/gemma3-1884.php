

<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email (important for security)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Assumes you have a function to fetch user by email
    if ($user === null) {
        error_log("User with email $email not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token
    $reset_token = generate_unique_token();

    // 4. Store Token in Database (Temporary - Expire after some time)
    //    - Key: User ID
    //    - Value: Token
    save_reset_token($user['id'], $reset_token);

    // 5. Send Password Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com"; // Replace with your email
    $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($reset_token) . "'>here</a> to reset your password.";

    if (send_email($user['email'], $subject, $headers, $message)) {
        return true;
    } else {
        error_log("Failed to send password reset email.");  // Log for debugging
        return false;
    }
}


/**
 * Dummy functions for illustration purposes.
 * Replace these with your actual implementation.
 */

function getUserByEmail(string $email): ?array
{
    // Replace this with your actual database query to fetch the user.
    // Example:
    // $query = "SELECT * FROM users WHERE email = ?";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$email]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $user ?: null;

    // Dummy data for testing
    $dummy_users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'securepass']
    ];
    foreach($dummy_users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}

function save_reset_token(int $userId, string $token): void
{
    // Implement your database logic here to save the token
    // Example:
    // $query = "INSERT INTO password_resets (user_id, token, expiry_date) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$userId, $token, time() + 3600]); // Token expires in 1 hour
}

function send_email(string $to, string $subject, string $headers, string $message): bool
{
    // Implement your email sending logic here.
    // Use a library like PHPMailer: https://github.com/PHPMailer/PHPMailer
    // For example:
    // $mail = new PHPMailer(true);
    // $mail->SetFrom('your_email@example.com', 'Your Name');
    // $mail->AddAddress($to);
    // $mail->SetSubject($subject);
    // $mail->MsgBody($message, 'html');
    // return $mail->send();

    // Dummy implementation - just return true for testing
    echo "Sending email to $to with subject $subject
";
    return true;
}



// Example Usage (for testing)
// You would typically call this function from a form submission.
// For testing, you can call it directly
// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to $email.
";
// } else {
//     echo "Failed to send password reset email.
";
// }
?>
