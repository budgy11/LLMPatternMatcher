

<?php

// Assuming you have a database connection established (e.g., $db)
// and have a 'users' table with a 'email' field.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided.");
    return false;
  }

  // 2. Check if the user exists
  $user = get_user_by_email($email); // Assuming this function exists - defined below

  if ($user === null) {
    error_log("User with email $email not found.");
    return false;
  }

  // 3. Generate a unique token
  $token = generate_unique_token();

  // 4. Store the token in the database associated with the user
  $result = store_token_for_user($user['id'], $token);

  if (!$result) {
    error_log("Failed to store token for user $email.");
    return false;
  }

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Click on this link to reset your password: " .  $_SERVER['PHP_SELF'] . "?token=" . urlencode($token); //  IMPORTANT: Security Considerations below!
  $headers = "From: your_email@example.com\r
";

  if (send_email($email, $subject, $message, $headers) ) {
    return true;
  } else {
    error_log("Failed to send password reset email to $email.");
    // Consider deleting the token if email sending fails.
    delete_token_for_user($user['id']); // Rollback
    return false;
  }
}


//  Dummy functions for demonstration - Replace with your actual implementations

/**
 * Retrieves a user from the database based on their email.
 *
 * @param string $email The user's email address.
 * @return null|array  An associative array representing the user, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
  // Replace this with your actual database query
  // This is a placeholder - use your database connection
  $users = [
    ['id' => 1, 'email' => 'test@example.com'],
    ['id' => 2, 'email' => 'another@example.com']
  ];

  foreach ($users as $user) {
    if ($user['email'] === $email) {
      return $user;
    }
  }
  return null;
}


/**
 * Generates a unique token for password reset.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Recommended for security
}


/**
 * Stores a token for a user in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if the token was successfully stored, false otherwise.
 */
function store_token_for_user(int $userId, string $token): bool
{
  // Replace this with your actual database insertion logic
  // Example:
  // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 30 MINUTE)";
  // Execute the query and check the result.

  // Placeholder:
  return true;
}


/**
 * Deletes a token for a user from the database.
 *
 * @param int $userId The ID of the user.
 * @return bool True if the token was successfully deleted, false otherwise.
 */
function delete_token_for_user(int $userId): bool
{
  // Replace this with your actual database query
  // Example:
  // $query = "DELETE FROM password_tokens WHERE user_id = $userId";
  // Execute the query and check the result.

  // Placeholder:
  return true;
}


/**
 * Sends an email.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $body The email body.
 * @param string $headers Email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email(string $to, string $subject, string $body, string $headers): bool
{
    // Replace this with your actual email sending implementation
    // (e.g., using PHP's mail() function or a library like PHPMailer)

    // Example (using the mail() function - be aware of limitations and security):
    // return mail($to, $subject, $body, $headers);

    // Placeholder - always return true to prevent errors during testing
    return true;
}


// Example Usage (demonstration)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  if (isset($email) && forgot_password($email)) {
    echo "Password reset email sent to " . $email;
  } else {
    echo "Failed to reset password.";
  }
}
?>
