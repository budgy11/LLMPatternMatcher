

<?php

// Database configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting the password reset.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password($email)
{
    // 1. Validate Email (Basic)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided."); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        return false; // Or handle the connection error differently
    }

    $sql = "SELECT id, email FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $user_email = $result->fetch_assoc()['email'];

        // 3. Generate a Unique Token/Reset Code
        $reset_code = bin2hex(random_bytes(32)); // More secure than simple random numbers.

        // 4. Store the Token (Temporary - Consider expiration)
        $sql_insert = "INSERT INTO password_resets (user_id, reset_code, expires_at) VALUES ($user_id, '$reset_code', NOW() + INTERVAL 24 HOUR)"; // Expires in 24 hours.  Adjust as needed.
        if (!$conn->query($sql_insert)) {
            error_log("Error inserting reset code: " . $conn->error);
            return false;
        }

        // 5. Send Password Reset Email
        $to = $email;
        $subject = 'Password Reset';
        $message = "Please click the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=$reset_code'</a>";
        $headers = "From: your_email@example.com" . "\r
";
        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            error_log("Failed to send password reset email.");
            // Optionally, delete the reset code if email sending fails.
            $conn->query("DELETE FROM password_resets WHERE user_id = $user_id AND reset_code = '$reset_code'");
            return false;
        }
    } else {
        return false; // User not found
    }

    // If it gets here, something went wrong (should be caught by the error_log)
    return false;
}


// --- Example Usage (For Testing -  Remove in Production!) ---
// Replace with a real email address for testing.
$email_to_reset = 'testuser@example.com'; // Replace with a test email

if (isset($_GET['reset'])) {
    $reset_code = $_GET['reset'];
    if (forgot_password($reset_code)) {
        echo "Password reset email sent to " . $reset_code . ".  Check your email.";
    } else {
        echo "Error: Password reset failed.";
    }
} else {
    // Display a form to trigger the reset process
    echo '<form action="" method="get">';
    echo '<input type="hidden" name="reset" value="';
    echo bin2hex(random_bytes(32));
    echo '">'; //  Important:  This is just a placeholder.
    echo '<input type="submit" value="Request Password Reset">';
    echo '</form>';
}

?>
