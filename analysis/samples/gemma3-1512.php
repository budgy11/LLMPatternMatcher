

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2. Check if User Exists
  $user = get_user_by_email($email); // Implement this function (see below)
  if (!$user) {
    return false; // User not found
  }

  // 3. Generate a Unique Token
  $token = generate_unique_token(); // Implement this function (see below)

  // 4. Store Token in Database
  $token_id = save_token_to_db($user->id, $token); // Implement this function (see below)
  if (!$token_id) {
    return false;
  }

  // 5. Send Password Reset Email
  $subject = "Password Reset Request for " . $user->email;
  $message = "Please click on the following link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset_token=" . $token; // Dynamically construct the link
  $headers = "From: Your Website <your.website@example.com>";

  if (mail($email, $subject, $message, $headers)) {
    // Success!
    return true;
  } else {
    // Failure to send email
    // Handle error appropriately (e.g., log it)
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}

// Helper Functions (Implement these based on your database and system)

/**
 * Retrieves a user from the database based on email.
 *
 * @param string $email The user's email address.
 * @return User|null  A User object on success, null if not found.
 */
function get_user_by_email(string $email): ?User {
    // Replace this with your actual database query
    // Example (assuming you have a User class):
    // $result = $db->query("SELECT * FROM users WHERE email = '$email'");
    // if ($result->num_rows > 0) {
    //     $user = new User($result->fetch_assoc());
    //     return $user;
    // }
    // return null;

    // Dummy user for testing
    $user = new User(['id' => 1, 'email' => 'test@example.com']);
    return $user;
}

/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token() {
  return bin2hex(random_bytes(32)); // Recommended: use a cryptographically secure random number generator
}


/**
 * Saves the token to the database, associated with the user.
 *
 * @param int $user_id The ID of the user.
 * @param string $token The token to save.
 * @return int|false The ID of the token on success, false on failure.
 */
function save_token_to_db(int $user_id, string $token) {
    // Replace this with your actual database query.
    // Example:
    // $result = $db->query("INSERT INTO password_tokens (user_id, token, expiry_date) VALUES ($user_id, '$token', NOW() + INTERVAL 30 DAY)");
    // return $db->insert_id;

    // Dummy save for testing.
    return 1;
}


// Example Usage (for testing/demo -  DO NOT use in production without proper security)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
  $email = $_POST['email'];
  if (forgot_password($email)) {
    echo "Password reset email sent successfully to " . $email;
  } else {
    echo "Failed to send password reset email.  Please try again.";
  }
}
?>
