    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
</form>


<?php

/**
 *  Forgot Password Function
 *
 *  This function handles the forgot password flow.
 *  It generates a unique token, stores it in the database with the user's email,
 *  sends an email to the user with a link to reset their password, and
 *  marks the token as used.
 *
 * @param string $email The email address of the user requesting the password reset.
 * @param string $reset_token A generated token (optional, defaults to a random string).
 * @return bool True if the reset process started successfully, false otherwise.
 */
function forgot_password(string $email, string $reset_token = '') {

  // 1. Generate a unique token if one wasn't provided
  if (empty($reset_token)) {
    $reset_token = generate_unique_token(); // Call a function to generate a unique token
  }

  // 2.  Store the token in the database
  $db_result = database_insert_token($email, $reset_token);

  if (!$db_result) {
    return false; // Token insertion failed
  }

  // 3. Send the password reset email
  if (!send_password_reset_email($email, $reset_token)) {
    //  Handle email sending failure - perhaps log it, retry, or return false.
    //  Important: Don't just silently fail.
    database_delete_token($email, $reset_token); // Clean up if email fails
    return false;
  }

  // 4. Mark the token as used (important for security)
  if (!mark_token_used($email, $reset_token)) {
    // Handle marking token as used failure.  Again, important to handle this.
    database_delete_token($email, $reset_token); // Clean up
    return false;
  }

  return true; // Password reset process started successfully
}


/**
 * Helper function to generate a unique token.
 * This is a simple example, you might use a more robust method.
 *
 * @return string A unique token.
 */
function generate_unique_token() {
  return bin2hex(random_bytes(32)); // Generates a 32-byte random string
}


/**
 * Placeholder function for database insertion of the token.
 *  This is a simplified example.  You'll need to adjust this to
 *  your database setup (e.g., using PDO or MySQLi).
 *
 * @param string $email The user's email address.
 * @param string $token The token to store.
 * @return bool True if the insertion was successful, false otherwise.
 */
function database_insert_token(string $email, string $token) {
  // Replace with your actual database connection and query logic
  // Example using a placeholder:
  $db_connection = get_database_connection(); // Get a connection (implementation depends on your setup)
  $query = "INSERT INTO user_tokens (email, token, used) VALUES ('$email', '$token', FALSE)";

  try {
    $result = $db_connection->query($query);
    return $result;
  } catch (Exception $e) {
    // Handle database errors appropriately
    error_log("Database error: " . $e->getMessage()); // Log the error
    return false;
  }
}



/**
 * Placeholder function to send the password reset email.
 * Replace with your email sending logic.
 *
 * @param string $email The user's email address.
 * @param string $token The password reset token.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_password_reset_email(string $email, string $token) {
  // Replace with your email sending logic (e.g., using PHPMailer or similar).
  // This is a placeholder - don't just return true
  // You *must* handle email sending failures!

  // Example Placeholder:
  error_log("Simulating password reset email sent to: " . $email . " with token: " . $token);  // Simulate sending
  return true; // In a real implementation, this should return true *only* if the email was successfully sent.
}


/**
 * Placeholder function to mark the token as used.
 * This prevents the token from being used again.
 *
 * @param string $email The user's email address.
 * @param string $token The token to mark as used.
 * @return bool True if the token was marked as used, false otherwise.
 */
function mark_token_used(string $email, string $token) {
  // Replace with your database update logic
  $db_connection = get_database_connection(); // Get a connection (implementation depends on your setup)
  $query = "UPDATE user_tokens SET used = TRUE WHERE email = '$email' AND token = '$token'";

  try {
    $result = $db_connection->query($query);
    return $result;
  } catch (Exception $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

/**
 * Helper function to get a database connection.  Replace with your connection logic
 *
 * @return PDO|MySQLi|null Returns a database connection object, or null if connection fails.
 */
function get_database_connection() {
    // Placeholder - replace with your actual database connection logic
    // Example using PDO:
    try {
        return new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    } catch (PDOException $e) {
        // Handle the exception appropriately, maybe log it and return null.
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}


/**
 *  Helper function to delete a token from the database.
 *  This is important to prevent abuse.
 *
 * @param string $email The user's email address.
 * @param string $token The token to delete.
 * @return bool True if the token was deleted, false otherwise.
 */
function database_delete_token(string $email, string $token) {
    // Replace with your actual database update logic
    $db_connection = get_database_connection();

    $query = "DELETE FROM user_tokens WHERE email = '$email' AND token = '$token'";

    try {
        $result = $db_connection->query($query);
        return $result;
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}
?>
