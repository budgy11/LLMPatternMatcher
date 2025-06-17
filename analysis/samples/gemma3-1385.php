

<?php

// IMPORTANT:  This is a simplified example for demonstration purposes.
// In a production environment, you should:
// 1.  Use a proper database for storing user credentials.
// 2.  Implement robust security measures (e.g., hashing passwords, input validation, CSRF protection).
// 3.  Consider using a dedicated password reset library or service.
// 4.  Store the reset token securely (e.g., in a database or a session).

class UserForgotPassword {

    private $db; // Database connection (replace with your actual connection)
    private $table_name = 'users'; // Name of your user table

    public function __construct($db) {
        $this->db = $db;
    }

    public function resetPassword($email) {
        // 1. Check if the email exists
        $result = $this->db->query("SELECT id, email FROM " . $this->table_name . " WHERE email = '$email'");
        $user = $result->fetch_assoc();

        if (!$user) {
            return false; // Email not found
        }

        // 2. Generate a unique, random reset token
        $token = bin2hex(random_bytes(32));

        // 3. Store the token in the database (associate with the user)
        $this->db->query("UPDATE " . $this->table_name . " SET reset_token = '$token' WHERE email = '$email'");

        // 4. (Optional) Send an email with the reset link
        //  - This part requires you to have an email sending functionality.
        //  - The email should contain a link like:  http://yourwebsite.com/reset-password?token=$token
        //  - You can use a templating engine like Blade to create the email.


        return true;
    }

    public function verifyTokenAndReset($token, $new_password) {
        // 1. Check if the token exists in the database
        $result = $this->db->query("SELECT id FROM " . $this->table_name . " WHERE reset_token = '$token'");
        $user = $result->fetch_assoc();

        if (!$user) {
            return false; // Token not found or invalid
        }

        // 2.  Update the user's password (USE HASHING!)
        //  - IMPORTANT:  Never store passwords in plain text.  Always hash them.
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $this->db->query("UPDATE " . $this->table_name . " SET password = '$hashed_password', reset_token = '',  status = 'active' WHERE id = '$user['id']'"); // Clear the reset token

        return true;
    }
}



// Example Usage (Illustrative - Adapt to your setup)

//  Assume you have a database connection object $db

//  1.  Reset Password Request
// $userForgotPassword = new UserForgotPassword($db);
// if ($userForgotPassword->resetPassword('user@example.com')) {
//   echo "Reset password link sent to user@example.com";
// } else {
//   echo "Failed to reset password.  User not found or email already in use.";
// }



// 2.  Verify Token and Reset Password
// $userForgotPassword = new UserForgotPassword($db);

// // Example:  User clicks the link, pastes the token and enters a new password
// $token = $_GET['token']; // Get the token from the URL (sanitize this thoroughly!)
// $new_password = $_POST['new_password'];

// if ($userForgotPassword->verifyTokenAndReset($token, $new_password)) {
//   echo "Password successfully reset!";
// } else {
//   echo "Invalid token or reset process failed.";
// }

?>
