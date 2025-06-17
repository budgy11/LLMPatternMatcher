

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password($email)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // 2. Check if user exists
    $stmt = $GLOBALS['conn']->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false;
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $stmt->close();

    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // More secure than using a simple number

    // 4. Store Token in Database (Temporary)
    $stmt = $GLOBALS['conn']->prepare("UPDATE users SET password_reset_token = ?, password_reset_expiry = ? WHERE email = ?");
    $stmt->bind_param("ssi", $token, time() + 3600, $email); // Token, expiry time (1 hour)
    $result = $stmt->execute();

    if (!$result) {
        $error = $GLOBALS['conn']->error;
        $stmt->close();
        // Log the error for debugging
        error_log("Password reset token update failed: " . $error);
        return false;
    }

    // 5. Send Password Reset Email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please reset your password by clicking on this link: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . $token;
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);


    // 6. Return True
    return true;
}



// Example Usage (for testing - this would normally be part of a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (forgot_password($email)) {
        echo "<p>Password reset email has been sent to $email.</p>";
    } else {
        echo "<p>Failed to reset password. Please try again.</p>";
    }
}
?>
