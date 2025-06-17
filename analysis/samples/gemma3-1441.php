

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with columns 'id', 'email', and 'password_hash'

function forgot_password($email, $db) {
    // 1. Check if the email exists in the database
    $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        // 2. Generate a unique, time-based token
        $token = bin2hex(random_bytes(32)); // Use bin2hex for a more secure random token
        // Use secure_password_hash if available to hash the password
        //  otherwise use password_hash
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);  // Hash the token

        // 3. Store the token in the database, associated with the user's ID
        $query = "UPDATE users SET password_reset_token = '$hashedToken' WHERE email = '$email'";
        if (!$db->query($query)) {
            // Handle error - perhaps log it or return an error message
            error_log("Error updating password reset token: " . $db->error); // Log for debugging
            return false;
        }

        // 4.  Send an email with the reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a> to reset your password.";  // Use $_SERVER['PHP_SELF'] to generate the URL
        $headers = "From: your_email@example.com";  // Replace with your email address
        mail($to, $subject, $message, $headers);

        // Return true to indicate success
        return true;
    } else {
        // Email doesn't exist
        return false;
    }
}

// Example Usage:  (This part is for demonstration, not for production)
/*
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$email = "test@example.com";  // Replace with the user's email address

if (forgot_password($email, $db)) {
    echo "Password reset email has been sent to $email.";
} else {
    echo "User with email $email not found.";
}

$db->close();
*/

?>
