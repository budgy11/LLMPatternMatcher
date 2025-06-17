

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was generated and emailed, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Check if the user exists
  $user = db_get_user_by_email($email); // Replace with your actual database query function
  if (!$user) {
    error_log("User with email " . $email . " not found.");
    return false;
  }

  // 3. Generate a unique token
  $token = generate_unique_token();

  // 4. Store the token and user ID in the database
  $result = db_insert_reset_token($user_id, $token);
  if (!$result) {
    error_log("Failed to insert reset token for user " . $email);
    return false;
  }

  // 5.  Build the reset link
  $reset_link = generate_reset_link($user->email, $token);

  // 6.  Send the reset link via email
  if (send_email(
    $user->email,
    "Password Reset Link",
    $reset_link  // Include the reset link in the email body
  )) {
    return true;
  } else {
    // Handle email sending failure (log it!)
    error_log("Failed to send password reset email to " . $user->email);
    // Optionally, delete the token from the database to prevent abuse.
    db_delete_reset_token($user_id, $token);
    return false;
  }
}

/**
 *  Helper Functions (Example Implementations - Adapt to your database)
 */

/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token() {
  return bin2hex(random_bytes(32)); // Generate a cryptographically secure random string
}

/**
 * Retrieves a user by their email address from the database.
 *
 * @param string $email The email address of the user.
 * @return  User object or false if not found.
 */
function db_get_user_by_email(string $email) {
  // Example using a hypothetical database query - Adapt to your database
  // Replace this with your actual database query
  global $db; // Assuming $db is your database connection
  $query = "SELECT * FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email); // "s" for string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    return new User($user);  // Assuming you have a User class to represent a user
  }

  return false;
}


/**
 * Inserts a new reset token into the database.
 *
 * @param int $user_id The ID of the user.
 * @param string $token The reset token.
 * @return int|false The ID of the inserted row, or false on failure.
 */
function db_insert_reset_token(int $user_id, string $token) {
  // Example using a hypothetical database query - Adapt to your database
  global $db; // Assuming $db is your database connection
  $query = "INSERT INTO reset_tokens (user_id, token, expiry) VALUES (?, ?, NOW())";
  $stmt = $db->prepare($query);
  $stmt->bind_param("is", $user_id, $token);
  $result = $stmt->execute();
  return $result ? $stmt->insert_id : false;
}



/**
 * Deletes a reset token from the database.
 *
 * @param int $user_id The ID of the user.
 * @param string $token The reset token.
 * @return bool True if the token was deleted, false otherwise.
 */
function db_delete_reset_token(int $user_id, string $token) {
    // Example using a hypothetical database query - Adapt to your database
    global $db; // Assuming $db is your database connection
    $query = "DELETE FROM reset_tokens WHERE user_id = ? AND token = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $user_id, $token);
    $result = $stmt->execute();
    return $result;
}


/**
 * Generates the reset link.  This should include the token.
 *
 * @param string $email The email address of the user.
 * @param string $token The reset token.
 * @return string The reset link.
 */
function generate_reset_link(string $email, string $token) {
  return "http://example.com/reset_password?token=" . urlencode($token) . "&email=" . urlencode($email);
}

/**
 *  Placeholder for sending email - Replace with your email sending library
 */
function send_email(string $to, string $subject, string $body) {
  //  In a real application, use a library like PHPMailer to handle email sending.
  //  For example:
  //  $mail = new PHPMailer\PHPMailer\PHPMailer();
  //  $mail->SetFrom('your@email.com', 'Your Website');
  //  $mail->AddAddress($to);
  //  $mail->Subject = $subject;
  //  $mail->Body = $body;
  //  return $mail->send();
  // This is just a placeholder -  return true for demonstration
  error_log("Simulating email sending to " . $to);
  return true;
}

/**
 * User Class (Example) -  Adapt to your User data structure
 */
class User {
  public $id;
  public $email;
  public $password; // or other user data

  public function __construct($data) {
    $this->id = isset($data['id']) ? $data['id'] : null;
    $this->email = $data['email'];
    $this->password = $data['password'];
  }
}
?>
