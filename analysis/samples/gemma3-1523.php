    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you'd want to add:
// - Stronger security measures (e.g., hashing passwords properly)
// - Email validation
// - Input sanitization to prevent vulnerabilities like SQL injection
// - Logging for debugging and security audits
// - Rate limiting to prevent brute-force attacks

// **IMPORTANT:**  Never store passwords in plain text.  Always hash them.
// This example uses a placeholder for hashing, you MUST implement a secure hashing method.

class UserForgotPassword {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function resetPassword($email) {
        // 1. Check if the user exists
        $user = $this->db->query("SELECT id, email FROM users WHERE email = ?", [$email]); // Replace 'users' with your table name
        if ($user->rowCount() === 0) {
            return false; // User not found
        }

        // 2. Generate a unique, time-based token
        $token = bin2hex(random_bytes(32)); // Use a strong random number generator

        // 3. Store the token in the database, linked to the user's ID
        $this->db->query("UPDATE users SET reset_token = ? WHERE email = ?", [$token, $email]);


        // 4.  Send an email with a link containing the token.
        $subject = 'Password Reset Link';
        $to = $email;
        $from = 'your_email@example.com'; // Replace with your email
        $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset-password?token=" . $token; // Replace 'yourdomain.com'
        $headers = "From: " . $from . "\r
";
        $headers .= "Reply-To: " . $from . "\r
";

        mail($to, $message, $headers); // Using PHP's built-in mail function - may require configuration.


        return true;
    }

    public function verifyToken($token) {
        // 1. Check if the token exists in the database
        $user = $this->db->query("SELECT id, email FROM users WHERE reset_token = ?", [$token]);

        if ($user->rowCount() === 0) {
            return false; // Invalid token
        }

        // 2.  If the token exists, delete it from the database to invalidate it.
        $this->db->query("UPDATE users SET reset_token = NULL WHERE reset_token = ?", [$token]);

        return true;
    }

    public function setPasswordForUser($email, $newPassword) {
        // 1.  Securely hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // 2.  Update the user's password in the database
        $this->db->query("UPDATE users SET password = ? WHERE email = ?", [$hashedPassword, $email]);

        return true;
    }
}



// **Example Usage (illustrative - replace with your actual database connection)**
$db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

// 1. Initiate password reset
$email = "testuser@example.com"; // Replace with the user's email
$reset = new UserForgotPassword($db);
$reset->resetPassword($email);

echo "Password reset link sent to " . $email . "
";


//  Later, when the user clicks the link (in a separate page):
//  1.  Verify the token
//  2.  If the token is valid, set the user's password using the setPasswordForUser method
//  3.  Invalidate the token (delete from the database)
?>
