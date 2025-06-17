

<?php

/**
 * Forgot Password Function
 *
 * This function allows a user to request a password reset.
 * It generates a unique token and stores it in the database
 * along with the user's email.  A reset link is then emailed
 * to the user.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Basic validation - you should expand this)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));

    // 3. Hash the Token (Important for Security)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store Token and Email in Database
    // Assuming you have a database connection called $db
    // and a table named 'users' with columns 'email' and 'reset_token'
    $sql = "INSERT INTO users (email, reset_token) VALUES ('$email', '$hashed_token')";

    if ($db->query($sql) === false) {
        error_log("Database error: " . $db->error);  //Log database errors
        return false;
    }

    // 5. Send Email with Reset Link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://localhost/reset_password.php?token=$token"; // Construct the full URL
    $headers = "From: your_email@example.com"; // Replace with your email
    
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email: " . error_get_last()['message']);
        // Handle email sending failure (e.g., check mail settings, SMTP configuration)
        return false;
    }
}

// Example Usage (Simulated Database - Replace with your actual database connection)
// Assume $db is your database connection object

// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }

?>
