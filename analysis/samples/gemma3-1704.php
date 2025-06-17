
</body>
</html>


<?php

// IMPORTANT: This is a simplified example for demonstration purposes.
// In a production environment, you MUST implement robust security measures
// such as:
//  - Stronger password hashing algorithms (e.g., bcrypt, Argon2)
//  - Rate limiting to prevent brute-force attacks
//  - Email verification for reset links
//  - Thorough input validation and sanitization
//  - Logging all password reset attempts

class PasswordReset {

  private $db; // Database connection

  public function __construct($db) {
    $this->db = $db;
  }

  public function resetPassword($email) {
    // 1. Check if the email exists in the database
    $stmt = $this->db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' for string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $stmt->close();
      return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();

    // 2. Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32));  // Generates a secure random token

    // 3. Store the token in the database, associated with the user
    $stmt = $this->db->prepare("UPDATE users SET reset_token = ? WHERE id = ?");
    $stmt->bind_param("ss", $resetToken, $userId);
    $stmt->execute();
    $stmt->close();

    // 4.  Send an email with the reset link.  (This is outside the core function for clarity)
    //  -  You'll need to have a function to send emails.
    //  -  The email should contain a link like:  `https://yourwebsite.com/reset-password?token=$resetToken`

    return $resetToken; // Return the token to the user
  }

  public function verifyResetToken($token) {
    // 1. Check if the token exists in the database
    $stmt = $this->db->prepare("SELECT id FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $stmt->close();
      return false; // Token not found
    }

    $userId = $result->fetch_assoc()['id'];
    $stmt->close();

    // 2.  Check if the token is still valid.  (Implement an expiration time for the token)
    //  -  This example doesn't include an expiration time for simplicity,
    //     but you should *always* add one.

    return true;  // Token is valid (for this simplified example)
  }

  public function deleteResetToken($token) {
    // 1. Check if the token exists in the database
    $stmt = $this->db->prepare("SELECT id FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $stmt->close();
      return false; // Token not found
    }

    $userId = $result->fetch_assoc()['id'];
    $stmt->close();

    // 2. Delete the token from the database
    $stmt = $this->db->prepare("DELETE FROM users WHERE reset_token = ? AND id = ?");
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();
    $stmt->close();

    return true;
  }
}


// Example Usage (Illustrative - Replace with your actual database setup)
//  -  This demonstrates how the class is used. It doesn't create a full application.
/*
$db = new mysqli("localhost", "username", "password", "database_name");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$reset = new PasswordReset($db);

// Reset password for user with email 'test@example.com'
$resetToken = $reset->resetPassword('test@example.com');

if ($resetToken) {
  echo "Reset token generated: " . $resetToken . "<br>";
  // Send email with reset link.
} else {
  echo "Failed to generate reset token.";
}

// Verify the token (only to demonstrate - in a real app, you'd verify
// through the reset link)
if ($reset->verifyResetToken($resetToken)) {
    echo "<br>Token is valid.";
} else {
    echo "<br>Token is invalid.";
}

// Delete the token (after the user has reset their password)
$reset->deleteResetToken($resetToken);
*/
?>
