

<?php

// Assuming you have a database connection established and named $db

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
  // 1. Validate Email (Basic)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Check if the user exists
  $user = getUserByEmail($email); // Implement this function
  if (!$user) {
    error_log("User not found for email: " . $email);
    return false;
  }

  // 3. Generate a unique token (for security)
  $token = generateUniqueToken(); // Implement this function

  // 4. Store the token and expiration date in the database
  // (This is where you'd typically update the user record)
  updateTokenForUser($user['id'], $token, time() + (24 * 60 * 60)); // Token expires in 24 hours

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Click the following link to reset your password: " . $_SERVER['PHP_SELF'] . "?token=$token"; // Use $_SERVER for security (more on this below)
  $headers = "From: your-website@example.com\r
";
  $result = sendEmail($email, $subject, $message, $headers); // Implement this function

  return $result;
}


/**
 *  Helper function to get user by email
 *  @param string $email
 *  @return array|null  User object or null if not found
 */
function getUserByEmail(string $email): ?array {
  // Replace this with your actual database query.
  // This is a placeholder to illustrate the concept.
  // Use prepared statements to prevent SQL injection.

  // Example using MySQLi
  $db = new mysqli("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  if ($db->connect_error) {
    error_log("Database connection error: " . $db->connect_error);
    return null;
  }

  $result = $db->query("SELECT * FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    return $user;
  }
  return null;
}


/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
  return bin2hex(random_bytes(32)); // Generate a 32-byte random string
}

/**
 * Sends an email.  This is a placeholder - replace with your email sending implementation.
 *
 * @param string $to      The recipient's email address.
 * @param string $subject The email subject.
 * @param string $body    The email body.
 * @param string $headers The email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendEmail(string $to, string $subject, string $body, string $headers): bool
{
  // This is a placeholder.  Replace with your actual email sending implementation
  // using a library like PHPMailer or SwiftMailer.

  // Example:  Echo the email for now
  error_log("Sending email to: " . $to . " with subject: " . $subject);
  error_log("Email body: " . $body);

  return true; // Indicate success (in a real implementation, check for errors)
}


/**
 *  Update the user's token in the database.
 *  @param int $userId
 *  @param string $token
 *  @param int $expirationTime
 *
 */
function updateTokenForUser(int $userId, string $token, int $expirationTime): void {
    // This is a placeholder - replace with your actual database update logic.
    // Use prepared statements to prevent SQL injection.
    // Example using MySQLi:
    $db = new mysqli("your_db_host", "your_db_user", "your_db_password", "your_db_name");
    if ($db->connect_error) {
        error_log("Database connection error: " . $db->connect_error);
        return;
    }

    $sql = "UPDATE users SET token = ?, expiration = ? WHERE id = ?";
    if ($db->query($sql, array($token, $expirationTime, $userId)) === false) {
        error_log("Query failed: " . $db->error);
    }
    $db->close();
}

?>
