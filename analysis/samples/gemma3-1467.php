

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password.
 *
 * @param string $email The email address associated with the account.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password($email)
{
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error
        return false;
    }

    // Check if the user exists
    $stmt = $GLOBALS['db']->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("User not found for email: " . $email); // Log the error
        return false;
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    // Store the token in the database associated with the user
    $stmt = $GLOBALS['db']->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->execute([$token, $email]);

    // Send the password reset email (you'll need to implement this)
    $reset_link = 'http://yourwebsite.com/reset_password.php?token=' . $token; // Customize this URL
    send_password_reset_email($email, $reset_link);

    return true;
}

/**
 * Sends a password reset email to the user.
 *
 * @param string $email The email address of the user.
 * @param string $reset_link The link to the password reset page.
 */
function send_password_reset_email($email, $reset_link)
{
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on the following link: " . $reset_link;

    // Replace with your email sending logic (e.g., using PHPMailer or similar)
    // Example using a placeholder for email sending:
    // echo "Email sent (implementation needed here)"; // Replace with actual email sending code
    error_log("Password reset email sent to: " . $email . " with link: " . $reset_link);  // Log the action
}

// Example Usage:
// Replace 'test@example.com' with the user's email address

// Assuming you have a database connection established:
// $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);

// Example call to the function
//if (isset($_POST['reset_email'])) {
//    $email = $_POST['reset_email'];
//    if (forgot_password($email)) {
//        echo "Password reset email has been sent to " . $email;
//    } else {
//        echo "Failed to reset password. Please check your email and try again.";
//    }
//}
?>
