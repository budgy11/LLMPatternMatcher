

<?php

// **Important Security Notes:**
// * This is a basic example for demonstration purposes ONLY.
// * **DO NOT** use this directly in a production environment without implementing robust security measures.
// * Always use strong password hashing algorithms (like bcrypt or Argon2) and proper input validation and sanitization.
// * Consider implementing multi-factor authentication for enhanced security.
// * This example relies on storing usernames and (optionally) a 'reset token' in a database. You'll need a database setup and a way to manage user accounts.

// **Configuration (Adjust these as needed)**
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// --- Functions ---

/**
 * Resets a user's password.
 *
 * @param string $username The username of the user requesting a password reset.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $username) {
  // 1. Validate Username (Important!)
  if (empty($username)) {
    error_log("Error: Username cannot be empty.");
    return false;
  }


  // 2. Check if user exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  $query = "SELECT id, email FROM users WHERE username = '$username'"; // Adjusted for username
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $email = $user['email'];

    // 3. Generate a Unique Reset Token
    $reset_token = bin2hex(random_bytes(32)); // Secure random token generation

    // 4. Store Token in Database (associate it with the user)
    $insert_query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($user_id, '$reset_token', NOW() + INTERVAL 1 HOUR)";
    if (!$conn->query($insert_query)) {
      error_log("Error inserting reset token: " . $conn->error);
      return false;
    }

    // 5. Send Password Reset Email (You'll need to implement this)
    // Example (replace with your email sending logic)
    $subject = "Password Reset Request";
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . $reset_token;
    $headers = "From: your_email@example.com\r
";

    //In a real application, use a library or function to send the email
    //Example: mail($email, $subject, $message, $headers);


    // 6. Return True (Success)
    return true;

  } else {
    // User not found
    return false;
  }

  // Close connection
  $conn->close();
}



/**
 *  Example usage (for testing or demonstration)
 */
// Example: Reset password for user "testuser"
$result = forgot_password("testuser");

if ($result) {
  echo "Password reset request sent for testuser. Check their email!";
} else {
  echo "Password reset failed for testuser.  User may not exist or there was an error.";
}

?>
