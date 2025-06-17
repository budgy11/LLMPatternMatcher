

<?php

// Assuming you have a database connection established (e.g., $db)
// and that you have a 'users' table with 'email' and 'password' columns.

/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Validate the email address.
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Check if the user exists.
  $user = get_user_by_email($email);
  if (!$user) {
    error_log("User with email " . $email . " not found."); // Log for debugging
    return false;
  }

  // 3. Generate a unique reset token.
  $reset_token = generate_unique_token();

  // 4. Store the token in the database, associated with the user.
  //    This is crucial for security.  Don't store the password directly.
  update_reset_token($user['id'], $reset_token);

  // 5. Send the reset email.
  $subject = "Password Reset Request";
  $message = "Click the link below to reset your password:
" .
             "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$reset_token'>" .
             "Reset Password" .
             "</a>";

  $headers = "From: your_email@example.com\r
";  // Replace with your actual email address
  $headers .= "Reply-To: your_email@example.com\r
";

  if (send_email($user['email'], $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send reset email for user " . $email);
    // Optionally, you could delete the token if email sending fails
    delete_reset_token($user['id']);
    return false;
  }
}


/**
 * Placeholder functions - Replace with your actual database logic.
 */

/**
 * Gets a user from the database by email.
 *
 * @param string $email The email address to search for.
 * @return array|null  An array representing the user data, or null if not found.
 */
function get_user_by_email(string $email): ?array {
  //  This is just a placeholder - Replace with your database query
  //  Example (using mysqli):
  //  $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
  //  if (mysqli_num_rows($result) > 0) {
  //    $row = mysqli_fetch_assoc($result);
  //    return $row;
  //  }
  //  return null;
  // Placeholder example
  $dummy_user = [
      'id' => 123,
      'email' => $email
  ];

  return $dummy_user;
}


/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
  return bin2hex(random_bytes(32)); // Use random_bytes for cryptographically secure tokens
}


/**
 * Updates the reset token in the database for a user.
 *
 * @param int $user_id The ID of the user.
 * @param string $reset_token The reset token.
 */
function update_reset_token(int $user_id, string $reset_token) {
  // Replace with your database update query.
  // Example (using mysqli):
  // mysqli_query($db, "UPDATE users SET reset_token = '$reset_token' WHERE id = $user_id");
  // Placeholder
  //echo "Updating token for user $user_id with $reset_token";
}

/**
 * Deletes the reset token from the database.
 *
 * @param int $user_id The ID of the user.
 */
function delete_reset_token(int $user_id) {
  // Replace with your database delete query.
  // Example (using mysqli):
  // mysqli_query($db, "DELETE FROM users WHERE id = $user_id");
  // Placeholder
}


/**
 * Sends an email.
 *
 * @param string $to The recipient email address.
 * @param string $subject The email subject.
 * @param string $message The email body.
 * @param string $headers Email headers.
 * @return bool True on success, false on failure.
 */
function send_email(string $to, string $subject, string $message, string $headers) {
  // Replace this with your email sending logic (e.g., using PHPMailer, SwiftMailer, etc.)
  // This is a placeholder for demonstration purposes.
  // In a real application, you would use a dedicated email library.
  echo "Simulating sending email to: " . $to . " with subject: " . $subject . "
";
  // Example using a mock send_email function.
  return true;  //Simulate success.
}



// Example Usage (This is just for demonstration)
// $email = "test@example.com"; // Replace with a valid email
// if (forgot_password($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email.";
// }

?>
