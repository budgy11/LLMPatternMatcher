

<?php

// Database connection (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Set error reporting to display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent, false otherwise.
 */
function forgotPassword($email)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log invalid emails
        return false;
    }

    // 2. Check if User Exists
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION); // For better error handling

    try {
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            error_log("User not found with email: " . $email); // Log user not found
            return false;
        }
    } catch (PDOException $e) {
        error_log("PDO Error: " . $e->getMessage()); // Log PDO errors
        return false;
    }

    // 3. Generate a Unique Token & Store it
    $token = bin2hex(random_bytes(32)); // Use bin2hex for security
    $token_hashed = password_hash($token, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token_hashed, time() + 3600, $email]); // Store token, expiry (1 hour)
    } catch (PDOException $e) {
        error_log("PDO Error updating user: " . $e->getMessage()); // Log PDO errors
        return false;
    }


    // 4.  Send Password Reset Email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please use the following link to reset your password:
" .
               "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";  // Use $_SERVER['PHP_SELF']
    $headers = "From: your_email@example.com"; // Replace with your email

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Handle email sending failure (log, etc.)
        error_log("Failed to send email for password reset to: " . $email);
        return false;
    }
}

// Example Usage (Simulated - this would come from a form submission)
if (isset($_GET['reset'])) {
    $resetToken = $_GET['reset'];
    $resetResult = forgotPassword($resetToken);

    if ($resetResult) {
        echo "Password reset email sent to " . $resetToken . ". Please check your inbox.";
    } else {
        echo "An error occurred while processing your password reset request. Please try again.";
    }
}
?>
