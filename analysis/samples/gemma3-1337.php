

<?php

// Assuming you have a database connection established (e.g., $db)
// and have the necessary credentials.  This is a simplified example.

/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if a reset email was sent, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (Important for Security)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the invalid email
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email); // Implement this function (see example below)

    if ($user === false) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique reset token
    $resetToken = generateUniqueToken();

    // 4. Store the token and user ID in the database (temporary)
    $result = storeTokenForUser($user['id'], $resetToken); // Implement this function

    if ($result === false) {
        error_log("Failed to store token for user " . $email);
        return false;
    }


    // 5. Send the password reset email
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: " .  base_url() . "/reset_password?token=" . urlencode($resetToken);
    $headers = "From: " . getSenderEmail() . "\r
";
    $sent = sendEmail($email, $subject, $message, $headers);

    if (!$sent) {
        error_log("Failed to send email for user " . $email);
        // Optional:  You might want to delete the token if the email sending fails,
        // to prevent the token from being used indefinitely.
        deleteTokenFromDB($user['id'], $resetToken);
        return false;
    }

    return true;
}


/**
 * Placeholder function to get a user by email.  Replace with your actual DB query.
 *
 * @param string $email The email address to search for.
 * @return array|bool An associative array representing the user data, or false if not found.
 */
function getUserByEmail(string $email)
{
    // **Replace this with your actual database query**
    // This is just an example, adjust to your database schema and driver.
    // For example, using MySQLi:
    // $query = "SELECT id, username, email FROM users WHERE email = ?";
    // $stmt = $db->prepare($query);
    // $stmt->bind_param("s", $email);
    // $stmt->execute();
    // $result = $stmt->get_result()->fetch_assoc();
    // $stmt->close();

    // This is a dummy example:
    $users = [
        ['id' => 1, 'username' => 'testuser', 'email' => 'test@example.com'],
        ['id' => 2, 'username' => 'anotheruser', 'email' => 'another@example.com']
    ];
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return false;
}



/**
 * Placeholder function to generate a unique token.  Use a robust random string generator.
 *
 * @return string A unique token.
 */
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Creates a 32-byte random string
}



/**
 * Placeholder function to store the token in the database.  Replace with your actual DB insertion.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True on success, false on failure.
 */
function storeTokenForUser(int $userId, string $token) {
    // **Replace this with your actual DB insertion**
    // For example, using MySQLi:
    // $query = "INSERT INTO password_tokens (user_id, token, expiry_date) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($query);
    // $stmt->bind_param("iii", $userId, $token, date('Y-m-d', time() + 3600)); // Expires in 1 hour
    // $result = $stmt->execute();
    // $stmt->close();

    // Dummy example (doesn't persist data - just for demonstration)
    return true;
}


/**
 * Placeholder function to delete the token from the database.  Important for security.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to delete.
 * @return bool True on success, false on failure.
 */
function deleteTokenFromDB(int $userId, string $token) {
    // **Replace this with your actual DB query**
    // For example, using MySQLi:
    // $query = "DELETE FROM password_tokens WHERE user_id = ? AND token = ?";
    // $stmt = $db->prepare($query);
    // $stmt->bind_param("is", $userId, $token);
    // $result = $stmt->execute();
    // $stmt->close();
    // Dummy example
    return true;
}


/**
 * Placeholder function to send the password reset email. Replace with your email sending logic.
 *
 * @param string $to       The recipient's email address.
 * @param string $subject  The email subject.
 * @param string $message  The email body.
 * @param string $headers  The email headers.
 * @return bool True on success, false on failure.
 */
function sendEmail(string $to, string $subject, string $message, string $headers) {
    // **Replace this with your actual email sending logic**
    //  This is just a placeholder to simulate success or failure
    //  Use a proper email library (e.g., PHPMailer) for real email sending
    //  Example:
    //  $mail = new PHPMailer(true);
    //  $mail->addAddress($to);
    //  $mail->setFrom("youremail@example.com", "Your Name");
    //  $mail->addReplyTo("youremail@example.com");
    //  $mail->Subject = $subject;
    //  $mail->MsgBody = $message, "UTF-8";
    //  return $mail->send();

    return true;  // Simulating success
}


function getSenderEmail() {
    return "noreply@example.com";
}

// Example Usage (Demonstration)
// In a real application, you would collect the email from a form.
// For this example, we'll hardcode it.
$emailToReset = 'test@example.com';  // Replace with the actual email

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset;
} else {
    echo "Failed to send password reset email to " . $emailToReset;
}

?>
