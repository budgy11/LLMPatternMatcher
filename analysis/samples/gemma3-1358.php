

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets the password for a user.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgotPassword(string $email)
{
    global $db; // Assuming $db is your database connection

    // Sanitize and validate the email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error
        return false;
    }

    // Check if the user exists
    $user = $db->query("SELECT id, username FROM users WHERE email = '$email'");

    if ($user->num_rows === 0) {
        error_log("User with email '$email' not found.");
        return false;
    }

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(32));

    // Hash the token (very important for security)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);


    // Store the token in the database, associated with the user
    $result = $db->query("
        INSERT INTO password_resets (user_id, token, expires_at)
        VALUES ($user->fetch_assoc()['id'], '$hashed_token', NOW() + INTERVAL 24 HOUR)
    ");

    if (!$result) {
        error_log("Error inserting password reset token: " . $db->error);
        return false;
    }


    // Send the password reset email
    $to = $email;
    $subject = "Password Reset";
    $message = "To reset your password, please click on the following link: " . $_SERVER['REQUEST_SCHEME'] . "://localhost/reset_password.php?token=" . $token; //Replace localhost with your actual domain
    $headers = "From: noreply@example.com\r
";
    mail($to, $message, $headers);

    return true;
}


// Example Usage (for testing - DON'T include this in a deployed application)
// Replace with your actual database connection details and setup
/*
$email = "test@example.com";

if (forgotPassword($email)) {
    echo "Password reset email sent to $email";
} else {
    echo "Failed to send password reset email.";
}
*/

?>
