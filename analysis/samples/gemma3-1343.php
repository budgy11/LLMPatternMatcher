

<?php

// This is a simplified example and should be used with caution.
// In a production environment, you *must* implement proper security measures
// like:
//  - Sending reset links via HTTPS
//  - Using a token that expires after a short period
//  - Using a database with password hashing
//  - Validating email addresses
//  - Rate limiting

class UserForgotPassword {

    private $db; // Database connection

    public function __construct($db) {
        $this->db = $db;
    }

    public function resetPassword($email) {
        // 1. Check if the email exists in the database
        $stmt = $this->db->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $stmt->close();
            return false; // Email not found
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        // 2. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generate a secure random token

        // 3.  Store the token in the database for the user. This is key!
        $stmt = $this->db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, time() + 3600); // expiry set to 1 hour. Change as needed.
        $result = $stmt->execute();

        if (!$result) {
            // Handle the error
            error_log("Error updating user reset token: " . print_r($stmt->error, true));
            return false;
        }

        // 4.  Create a reset link
        $resetLink = $this->generateResetLink($user['id'], $token);

        // 5.  Send the reset link to the user (implementation omitted for brevity - see below)
        //    This would typically involve sending an email.
        //    This example just logs the link.

        error_log("Reset password link sent to: " . $resetLink);
        return true;
    }


    private function generateResetLink($userId, $token) {
        return "https://yourdomain.com/reset-password?token=$token&userId=$userId";
    }

}



// Example usage (assuming you have a database connection $db)
//
//  $userForgotPassword = new UserForgotPassword($db);
//  $resetResult = $userForgotPassword->resetPassword("user@example.com");
//  if ($resetResult) {
//      echo "Reset link sent to user@example.com";
//  } else {
//      echo "Failed to send reset link.";
//  }

?>
