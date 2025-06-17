

<?php

/**
 * Forgets a user's password and initiates a password reset process.
 *
 * @param string $email The user's email address.
 * @param string $reset_token A unique token generated for the password reset request.
 * @param string $db_connection A valid database connection object.
 *
 * @return bool True if the password reset token is found and the password is reset, 
 *             false otherwise.
 */
function forgot_password(string $email, string $reset_token, $db_connection) {
    // 1. Verify the token exists
    $sql = "SELECT id, email FROM users WHERE email = ? AND reset_token = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("ss", $email, $reset_token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $userEmail = $user['email'];

        // 2.  Reset the password (Here you would typically change the password)
        //  This is just a placeholder.  In a real application, you would
        //  generate a random password, store it securely, and notify the user.

        // Example: Reset to a temporary password
        $newPassword = "temporarypassword123"; //  Replace with a secure password generation
        $sql = "UPDATE users SET password = ?, reset_token = '', last_password_reset = NOW() WHERE id = ?";
        $stmt = $db_connection->prepare($sql);
        $stmt->bind_param("ssi", $newPassword, $userId, $userEmail);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // 3. Send a password reset email (Include the new password)
            $to = $userEmail;
            $subject = 'Password Reset';
            $message = "Please use the following password to log in: $newPassword";
            $headers = "From: your-website@example.com\r
";
            mail($to, $subject, $message, $headers);

            return true; // Password reset successful
        } else {
            return false; // Failed to reset password
        }

    } else {
        return false; // Token not found
    }
}

// Example Usage (Illustrative - Requires a database connection)
// $db_connection = new mysqli("localhost", "username", "password", "database_name");
// if ($db_connection->connect_error) {
//     die("Connection failed: " . $db_connection->connect_error);
// }

// $email = "test@example.com"; // Replace with a valid email address
// $reset_token = "xyz123"; // Generate a unique token (e.g., using a library or random string)

// if (forgot_password($email, $reset_token, $db_connection)) {
//     echo "Password reset initiated.  Check your email for instructions.";
// } else {
//     echo "Password reset failed. Please check the email address and token.";
// }

// Close the database connection (Important!)
// $db_connection->close();

?>
