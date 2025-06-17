

// Delete the token after reset is confirmed
$sql = "DELETE FROM password_resets WHERE token = '$token' AND user_id = $user_id";
if ($conn->query($sql) === TRUE) {
    // Token deleted successfully
}


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, secure token, sends an email with a
 * reset link, and updates the user's password in the database.
 *
 * @param string $email The user's email address.
 * @param PDO $pdo  The PDO database connection object.
 * @return bool True if the password reset process started successfully,
 *             false otherwise (e.g., email not sent).
 */
function forgot_password(string $email, PDO $pdo) {
    // 1. Generate a secure, unique token.  Use a strong random string.
    $token = bin2hex(random_bytes(32));

    // 2. Prepare the reset token and user ID for the database.
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user_id = null;
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    if (!$user_id) {
        return false; // User not found
    }

    // 3.  Store the token in the database, linked to the user.
    $sql = "INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $expires_at = time() + (2 * 60 * 60); // Expires in 2 hours
    $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_INT);
    $stmt->execute();



    // 4.  Send the password reset email.
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '"' . ' >Reset Password</a>';
    $headers = 'From: your_email@example.com' . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Log an error if the email fails to send
        error_log("Failed to send email for password reset: " . $email);
        return false;
    }
}


// Example Usage (Illustrative -  Don't include this directly in your main application code!)
// To use this, you'd typically have it called from a form submission handler.
// This example shows how you *would* call it, but this is just for demonstration.

//  This is just an example. Replace with your actual database connection.
/*
$pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgot_password($email, $pdo)) {
        echo "Password reset email has been sent to $email.";
    } else {
        echo "Failed to initiate password reset.";
    }
}
*/
?>
