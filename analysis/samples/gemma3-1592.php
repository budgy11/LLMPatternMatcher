

<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // Validate email (basic check - you should use a more robust validation method)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided for password reset."); // Log invalid emails
        return false;
    }

    // Check if the user exists
    $user = get_user_by_email($email); // Function to retrieve user by email

    if (!$user) {
        error_log("User with email $email not found.");
        return false;
    }

    // Generate a unique token and timestamp
    $token = generate_unique_token();
    $timestamp = time();

    // Store the token and timestamp in the database (for now, we'll use a simplified approach - consider a more secure method)
    // This is the key part that needs to be adapted to your database schema.
    // Ideally, you'd store the token and timestamp in a table specifically designed for password resets.

    $reset_data = [
        'user_id' => $user['id'],
        'token' => $token,
        'timestamp' => $timestamp,
    ];

    // Store the data (Replace this with your database query)
    // Example:
    // $result = mysqli_query($db, "INSERT INTO password_resets (user_id, token, timestamp) VALUES ('$user_id', '$token', '$timestamp')");
    // if (!$result) {
    //     error_log("Error storing password reset token in database.");
    //     return false;
    // }

    // Send the password reset email
    $subject = "Password Reset Request";
    $message = "To reset your password, please click on the following link: " . get_reset_link($token);
    $headers = "From: your_email@example.com"; // Replace with your email address
    $sent = send_email($email, $subject, $message, $headers);

    if ($sent) {
        // Optionally, you can delete the temporary reset data after sending the email (for security)
        // delete_password_reset_token($token);
        return true;
    } else {
        error_log("Failed to send password reset email to $email.");
        return false;
    }
}


// --- Helper Functions (Implement these according to your database and email setup) ---

/**
 * Retrieves a user by their email address.
 *
 * @param string $email The email address to search for.
 * @return array|null An associative array representing the user, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
    // Replace this with your actual database query
    // Example using a MySQL database:
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if ($result) {
    //     if (mysqli_num_rows($result) > 0) {
    //         $user = mysqli_fetch_assoc($result);
    //         return $user;
    //     }
    // }
    // return null;
}

/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32));
}

/**
 * Creates a URL for the password reset link.
 *
 * @param string $token The token.
 * @return string The password reset link URL.
 */
function get_reset_link(string $token): string
{
    return "your_website_url/reset_password?token=" . urlencode($token); // Replace your website URL
}

/**
 * Sends an email.  (Implement this using your email sending library)
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $message The email body.
 * @param string $headers The email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email(string $to, string $subject, string $message, string $headers): bool
{
    //  Replace this with your email sending implementation.  Use a library like PHPMailer:
    //   https://peps.php.net/phpmassmailer
    // Example (placeholder):
    // $mail = new PHPMailer(true);
    // $mail->SetFrom("your_email@example.com", "Your Name");
    // $mail->AddAddress($to, "User Name");
    // $mail->Subject = $subject;
    // $mail->MsgBody = $message);
    // return $mail->Send();
    return true; // Placeholder - replace with actual email sending logic
}


//  Example Usage (for testing - in a real application, this would come from the form submission)
// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email has been sent to $email.";
// } else {
//     echo "Failed to send password reset email.";
// }

?>
