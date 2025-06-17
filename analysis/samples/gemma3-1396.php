

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset link has been sent, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email (Basic) -  Expand this for more robust validation if needed.
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log the invalid email
    return false;
  }

  // 2. Check if the user exists.  This is crucial.
  $user = getUserByEmail($email); // Assume you have a function to fetch the user
  if (!$user) {
    error_log("User with email " . $email . " not found.");
    return false;
  }

  // 3. Generate a Unique Token (Important for security)
  $token = generateUniqueToken();

  // 4. Store the Token and User ID in the Database
  $result = storeTokenForUser($user->id, $token); // Assume you have a function for this
  if (!$result) {
    error_log("Failed to store token for user " . $email);
    return false;
  }

  // 5. Send the Password Reset Email
  $subject = "Password Reset";
  $message = "To reset your password, please click on the following link: " .  base_url() . "/reset-password?token=" . $token;  // Use your base URL
  $headers = "From: " . get_option('admin_email') . "\r
"; //Replace with your email
  $result = sendEmail($email, $subject, $message, $headers);
  if (!$result) {
    error_log("Failed to send email for password reset to " . $email);
    // Optionally, you might try deleting the token if email sending fails.
    // deleteTokenForUser($user->id, $token);
    return false;
  }

  return true;
}


/**
 *  Helper function to get a user by email.  Replace with your actual implementation.
 * @param string $email
 * @return object|null User object or null if not found.
 */
function getUserByEmail(string $email) {
    // Replace this with your database query logic
    // This is a placeholder example.
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'secret']
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return new stdClass(); // Create a new object for the user
        }
    }

    return null;
}


/**
 * Generate a unique token.  Consider using a library for cryptographically secure random strings.
 * @return string
 */
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // More secure than mt_rand
}

/**
 *  Placeholder function to store the token in the database.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function storeTokenForUser(int $userId, string $token) {
    // Your database logic here.
    // For example:
    // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)";
    // executeQuery($query);
    return true;
}


/**
 *  Placeholder function to send an email.
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function sendEmail(string $to, string $subject, string $message, string $headers) {
    // Replace this with your email sending logic (e.g., using PHPMailer or similar).
    // This is a placeholder for demonstration purposes.

    // Simulate successful sending
    error_log("Simulated sending email to: " . $to . " with subject: " . $subject);
    return true;
}

/**
 *  Placeholder function to delete the token.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function deleteTokenForUser(int $userId, string $token) {
    //Your database logic here to delete the token record.
    return true;
}

/**
 *  Returns the base url of your website.  Useful for generating reset links.
 * @return string
 */
function base_url() {
    // Replace this with your actual base URL.
    return "http://localhost/your-website";
}


// Example Usage:
$email = "test@example.com";

if (forgotPassword($email)) {
    echo "Password reset email has been sent to " . $email . ".  Check your inbox.";
} else {
    echo "Failed to initiate password reset for " . $email;
}

?>
