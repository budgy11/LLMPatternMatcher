

<?php

// Database credentials - Replace with your actual credentials
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting the reset.
 * @return bool True if a reset email was sent successfully, false otherwise.
 */
function forgotPassword($email)
{
    // 1. Validate Input (Important for security!)
    if (empty($email)) {
        error_log("Error: Email address cannot be empty."); //Log the error for debugging
        return false;
    }

    // Sanitize the email (to prevent SQL injection)
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // 2. Check if the user exists
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION); // Throw exceptions on errors

    try {
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            error_log("User with email $email not found."); //Log the error for debugging
            return false;
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log database errors
        return false;
    } finally {
        $pdo = null; // Close the database connection
    }

    // 3. Generate a Unique Token and Store it
    $token = binrandom_bytes(32); // Generate a secure random token (32 bytes)
    $token_string = bin2hex($token);

    try {
        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry  WHERE email = :email");
        $stmt->bindParam(':token', $token_string, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600), PDO::PARAM_STR); // Token expires in 1 hour
        $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);

        $stmt->execute();

        // 4. Send the Password Reset Email (Implement this part)
        $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token_string;
        sendResetPasswordEmail($user['email'], $resetLink);

        return true;

    } catch (PDOException $e) {
        error_log("Database error during token creation/update: " . $e->getMessage()); // Log database errors
        return false;
    }
}



/**
 * Sends a password reset email to the specified email address.
 *
 * @param string $email The email address to send the email to.
 * @param string $resetLink The URL for the password reset page.
 */
function sendResetPasswordEmail($email, $resetLink)
{
    // Replace with your email sending logic (e.g., using PHPMailer or similar)
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on the following link: " . $resetLink;

    // Example using mail() -  This may not work reliably on all servers
    mail($to, $message, "From: your_email@example.com");

    // Consider using a more robust email library like PHPMailer.
}


// Example Usage (for testing - remove this from your production code)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    if (isset($email)) {
        if (forgotPassword($email)) {
            echo "Reset email sent to $email";
        } else {
            echo "Error: Could not send reset email.";
        }
    } else {
        echo "Error: Email not provided.";
    }
}
?>
