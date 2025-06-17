

<?php

// This is a simplified example and should be used with caution.
// In a production environment, implement robust security measures.

class PasswordReset {

    private $db; // Database connection

    public function __construct($db) {
        $this->db = $db;
    }

    public function resetPassword($email) {
        // 1. Check if the user exists
        $user = $this->getUserByEmail($email);

        if (!$user) {
            return false; // User not found
        }

        // 2. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Secure random token

        // 3. Store the token and expiration date in the database
        $this->saveToken($user->id, $token, time() + (24 * 60 * 60)); // Expires in 24 hours

        // 4. Email the user with the reset link
        $resetLink = $this->generateResetLink($user->email, $token);
        $subject = 'Password Reset';
        $message = 'Please click the link below to reset your password: ' . $resetLink;
        $headers = 'From: your_email@example.com' . "\r
"; // Replace with your email

        $result = mail($email, $subject, $message, $headers);

        if ($result === false) {
            // Mail failed, handle the error (log, etc.)
            return false;
        }

        return true;
    }


    private function getUserByEmail($email) {
        // Replace this with your actual database query
        // This is a placeholder. Adapt it to your database structure.
        // Example:
        // $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        // $stmt->bind_param("s", $email);
        // $stmt->execute();
        // $result = $stmt->get_result();

        // Mock User Class (replace with your actual User class)
        class User {
            public $id;
            public $email;

            public function __construct($id, $email) {
                $this->id = $id;
                $this->email = $email;
            }
        }

        // Example User
        $user = new User(1, 'test@example.com');
        return $user;
    }

    private function saveToken($userId, $token, $expiry) {
        // Replace this with your actual database query
        // Example:
        // $stmt = $this->db->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
        // $stmt->bind_param("iii", $userId, $token, $expiry);
        // $stmt->execute();
    }


    private function generateResetLink($email, $token) {
        return 'http://yourwebsite.com/reset-password?token=' . $token; // Replace with your reset link URL
    }
}

// Example Usage (replace with your actual database connection)
// IMPORTANT: This is just an example.  Do not use this directly in production!

// Mock Database Connection (Replace with your actual database connection)
$db = new mysqli('localhost', 'username', 'password', 'database');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create an instance of the PasswordReset class
$passwordReset = new PasswordReset($db);

// Reset password for a user (replace with a real email)
$email = 'test@example.com'; // Replace with the user's email
if ($passwordReset->resetPassword($email)) {
    echo "Password reset link sent to " . $email;
} else {
    echo "Failed to send password reset link.";
}

$db->close(); // Close the database connection
?>
