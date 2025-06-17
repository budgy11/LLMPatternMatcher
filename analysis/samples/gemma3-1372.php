

<?php

// This is a basic example and should be adapted for production use.
// It's crucial to implement proper security measures in a real-world application.

// **Important Security Considerations:**
// 1. NEVER store passwords in plain text. Always hash them with a strong hashing algorithm like bcrypt or Argon2.
// 2.  Implement rate limiting to prevent brute-force attacks.
// 3.  Use a token-based authentication system with an expiration time.  This is the preferred method.
// 4.  Send emails securely (HTTPS).
// 5.  Validate all input rigorously.
// 6.  Store session data securely (e.g., using a secure cookie with the `secure` flag set).

class UserAuthentication {

    private $db; // Database connection (replace with your actual database connection)

    public function __construct($db) {
        $this->db = $db;
    }

    public function forgotPassword($email) {
        // 1. Verify that the email exists in the database
        $result = $this->db->query("SELECT id, username FROM users WHERE email = '$email'");

        if ($result->num_rows == 0) {
            return false; // Email not found
        }

        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $username = $user['username'];

        // 2. Generate a unique, random token
        $token = bin2hex(random_bytes(32)); // Generate a strong random token

        // 3. Store the token and user ID in the database (for security, consider a separate table)
        //    This is a simplified example. A dedicated 'reset_tokens' table is recommended.
        $query = "INSERT INTO reset_tokens (user_id, token, expiry_timestamp) 
                  VALUES ($userId, '$token', NOW() + INTERVAL 24 HOUR)"; // Expire after 24 hours
        $this->db->query($query);

        // 4. Send an email with a reset link
        $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token;
        $to = $email;
        $subject = 'Password Reset';
        $message = "Please click the following link to reset your password: " . $resetLink;
        $headers = "From: your_email@example.com";

        mail($to, $subject, $message, $headers);


        return true; // Reset link sent successfully
    }

    public function verifyToken($token) {
        // 1. Check if the token exists in the database
        $result = $this->db->query("SELECT id FROM reset_tokens WHERE token = '$token' AND expiry_timestamp > NOW()");

        if ($result->num_rows > 0) {
            return true; // Token is valid and not expired
        } else {
            return false; // Token is invalid or expired
        }
    }

    public function deleteToken($token) {
        // Delete the reset token from the database
        $this->db->query("DELETE FROM reset_tokens WHERE token = '$token'");
    }
}


// Example Usage (replace with your actual database connection)

// Assume you have a database connection called $db
// Example database connection (replace with your database credentials)
$db = new mysqli("localhost", "your_username", "your_password", "your_database");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$userAuthentication = new UserAuthentication($db);

// Example: Reset password for user with email 'test@example.com'
$email = 'test@example.com';
if ($userAuthentication->forgotPassword($email)) {
    echo "Reset link sent to $email";
} else {
    echo "Failed to send reset link.";
}

// ... Later, after the user clicks the link and sets a new password ...

// Verify the token
if ($userAuthentication->verifyToken($token)) { // Replace 'token' with the actual token
   // Now you can proceed with updating the user's password in the database.
   echo "Token is valid.";
} else {
   echo "Invalid token.";
}

// Delete the token after successful password reset
$userAuthentication->deleteToken($token);
?>
