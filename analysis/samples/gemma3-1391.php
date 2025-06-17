

function generate_reset_token() {
    return bin2hex(random_bytes(32)); // Generate a 32-byte (256-bit) random string
}

// Usage
$resetToken = generate_reset_token();
echo $resetToken;


<?php

/**
 * Forgot Password Function
 *
 * Generates a temporary password and sends it to the user's email.
 *
 * @param string $email The user's email address.
 * @param string $to  The email address to send the password reset link to.
 * @param string $from The email address of the sender.  Should be a valid email address.
 * @param string $subject The subject of the email.
 * @param string $message The body of the email.
 * @param string $reset_token_life The lifespan of the reset token in seconds (default: 3600 - 1 hour).
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email, string $to, string $from, string $subject, string $message, int $reset_token_life = 3600) {
    // Sanitize the email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (empty($email)) {
        error_log("Invalid email address provided."); // Log for debugging
        return false;
    }

    // Generate a random reset token
    $reset_token = bin2hex(random_bytes(32)); // More secure than md5/sha1

    // Hash the token (optional, but recommended for security)
    $hashed_token = password_hash($reset_token, PASSWORD_DEFAULT);  //Using password_hash for secure hashing

    // Store the token and user ID in the database (replace with your database interaction)
    // This is a placeholder - you'll need to adapt this to your database structure
    $user_id = get_user_id_by_email($email); //  Assuming you have a function to get user ID from email
    if (!$user_id) {
        error_log("User with email {$email} not found."); // Log if user doesn't exist
        return false;
    }

    // Store the token in the database.  Replace this with your database logic
    // This example uses a simple array (for demonstration only)
    $reset_tokens = get_reset_tokens_by_user_id($user_id);
    $reset_tokens[] = ['token' => $reset_token, 'expiry' => time() + $reset_token_life];
    store_reset_tokens_by_user_id($user_id, $reset_tokens);


    // Construct the reset link
    $reset_url = "http://yourdomain.com/reset_password?token=" . urlencode($reset_token) . "&expiry=" . urlencode(time() + $reset_token_life);


    // Send the email
    if (send_email($to, $subject, $message, $reset_url)) {
        return true;
    } else {
        error_log("Failed to send email to {$to}");  // Log email sending failure
        // Optionally, you could delete the token from the database here if the email sending failed
        //  to avoid a potentially exposed token.  Be very careful when doing this.
        delete_reset_token_by_user_id($user_id, $reset_token);
        return false;
    }
}


/**
 * Placeholder Functions (Replace with your actual implementations)
 */

/**
 * Example function to get the user ID from email.  Replace with your database query.
 *
 * @param string $email The email address.
 * @return int|null The user ID, or null if not found.
 */
function get_user_id_by_email(string $email) {
    // Replace this with your actual database query
    // Example:
    // $db = new PDO(...);
    // $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    // $stmt->execute([$email]);
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $result['id'] ?? null;

    // Placeholder for demonstration
    if ($email === 'test@example.com') {
        return 123;
    }
    return null;
}

/**
 * Placeholder function to store reset tokens in the database.  Replace with your database logic.
 *
 * @param int $user_id The user ID.
 * @param array $reset_tokens The reset tokens to store.
 */
function store_reset_tokens_by_user_id(int $user_id, array $reset_tokens) {
    // Replace this with your database insertion logic
    // Example:
    // $db = new PDO(...);
    // $sql = "INSERT INTO reset_tokens (user_id, token, expiry) VALUES (:user_id, :token, :expiry)";
    // $stmt = $db->prepare($sql);
    // $stmt->execute([
    //     'user_id' => $user_id,
    //     'token' => $token,
    //     'expiry' => time() + $reset_token_life
    // ]);
}

/**
 * Placeholder function to get reset tokens for a user.
 *
 * @param int $user_id The user ID.
 * @return array The reset tokens.
 */
function get_reset_tokens_by_user_id(int $user_id) {
    // Replace this with your database query
    // Example:
    // $db = new PDO(...);
    // $stmt = $db->prepare("SELECT token, expiry FROM reset_tokens WHERE user_id = ?");
    // $stmt->execute([$user_id]);
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // return $result;

    // Placeholder for demonstration
    return [];
}


/**
 * Placeholder function to delete a reset token by user ID and token.
 *
 * @param int $user_id The user ID.
 * @param string $token The token to delete.
 */
function delete_reset_token_by_user_id(int $user_id, string $token) {
    // Replace this with your database deletion logic
    // Example:
    // $db = new PDO(...);
    // $sql = "DELETE FROM reset_tokens WHERE user_id = ? AND token = ?";
    // $stmt = $db->prepare($sql);
    // $stmt->execute([$user_id, $token]);
}



/**
 * Placeholder function to send an email. Replace with your email sending implementation.
 *
 * @param string $to The email address.
 * @param string $subject The email subject.
 * @param string $message The email body.
 * @param string $reset_url The reset password URL.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email(string $to, string $subject, string $message, string $reset_url) {
    // Replace this with your email sending implementation (e.g., using PHPMailer)
    // Example:
    // $mail = new PHPMailer(true);
    // $mail->SetFrom('your_email@example.com', 'Your Website');
    // $mail->AddAddress($to, 'User');
    // $mail->Subject = $subject;
    // $mail->MsgBody = $message;
    // $mail->Body = $message;
    // $mail->AddAttachment("attachment.jpg", "Image");
    // return $mail->Send();

    // Placeholder for demonstration
    error_log("Simulating email sending to {$to} with subject {$subject} and body {$message} and URL {$reset_url}");
    return true;
}



?>
