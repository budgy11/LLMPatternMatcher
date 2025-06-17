
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br><br>

        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// This is a simplified example for demonstration purposes.
// In a production environment, you *must* use secure password reset mechanisms
// like generating unique, cryptographically secure tokens and handling them securely.

class PasswordReset
{
    private $db; // Database connection (replace with your database connection)

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function resetPassword($email)
    {
        // 1. Check if the email exists in the database
        $result = $this->db->query("SELECT id, email FROM users WHERE email = '$email'");
        $user = $result->fetch_assoc();

        if (!$user) {
            return false; // Email not found
        }

        // 2. Generate a unique, secure token
        $token = bin2hex(random_bytes(32)); // Generate 32 bytes for a strong token

        // 3.  Store the token and user ID in a temporary table (for security)
        $this->db->query("CREATE TEMPORARY TABLE IF NOT EXISTS reset_tokens (user_id INT, token VARCHAR(64))");
        $this->db->query("INSERT INTO reset_tokens (user_id, token) VALUES ($user['id'], '$token')");

        // 4. Send a password reset email (replace with your email sending logic)
        $subject = "Password Reset Request";
        $message = "Click <a href='" . $this->getPasswordResetLink($user['id']) . "'>here</a> to reset your password.";
        $headers = "From: your_email@example.com"; // Replace with your email address
        $this->sendEmail($subject, $message, $headers, $email);

        // 5.  Cleanup (Remove the temporary table)
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS reset_tokens");

        return true;
    }


    private function getPasswordResetLink($userId)
    {
        return "https://yourwebsite.com/reset-password?token=" . bin2hex($userId);  // Replace your website URL
    }


    private function sendEmail($subject, $message, $headers, $email)
    {
        // Implement your email sending logic here.
        // Use a library like PHPMailer or similar for robust email sending.
        // Example (using a placeholder):
        // mail($email, $subject, $message, $headers);
        echo "Email sending logic (replace with your actual implementation).
";
    }


}


// Example Usage (Replace with your actual database connection)
$db = new mysqli("localhost", "your_username", "your_password", "your_database");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


$passwordReset = new PasswordReset($db);

$email = "user@example.com"; // Replace with the email you want to reset

if ($passwordReset->resetPassword($email)) {
    echo "Password reset email sent to $email.
";
} else {
    echo "Error: Password reset failed.  Check your email address and database connection.
";
}

$db->close();  // Close the database connection
?>
