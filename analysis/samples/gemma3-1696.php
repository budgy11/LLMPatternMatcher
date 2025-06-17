

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token and sends an email to the user
 * with a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $baseUrl The base URL of the website.  Needed for generating the reset link.
 * @return bool True if an email was sent, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided.");
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a strong random token
    // Store the token in the database.  Replace 'users' and 'password_reset_token' with your actual table and column names.
    // This assumes you have a database connection already established.
    $mysqli = new mysqli("localhost", "your_username", "your_password", "your_database");
    if ($mysqli->connect_errno) {
        error_log("Failed to connect to database.");
        return false;
    }

    $sql = "INSERT INTO password_reset_tokens (user_email, reset_token, expiry_timestamp) VALUES (?, ?, ?)";
    if ($mysqli->query($sql) === false) {
        error_log("Query failed: " . $mysqli->error);
        $mysqli->close();
        return false;
    }

    $id = $mysqli->insert_id;
    $mysqli->close();



    // 3. Generate the reset link
    $resetLink = $baseUrl . "/reset_password.php?token=" . $token;


    // 4. Send the email
    $to = $email;
    $subject = "Password Reset";
    $message = "Click the following link to reset your password: " . $resetLink . "

" .
               "This link will expire in 24 hours.";
    $headers = "From: Your Website <your_email@example.com>"; // Replace with your actual email address

    if (mail($to, $message, $headers)) {
        return true;
    } else {
        // Log the error if the email fails to send
        error_log("Failed to send email to " . $email);
        // Attempt to delete the record if email fails
        $sql_delete = "DELETE FROM password_reset_tokens WHERE user_email = '$email'";
        if ($mysqli->query($sql_delete) === false) {
            error_log("Failed to delete password reset token from database: " . $mysqli->error);
        }
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual website URL and email)
// $email = "test@example.com";
// $baseUrl = "https://www.yourwebsite.com";
// $success = forgotPassword($email, $baseUrl);
//
// if ($success) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }
?>
