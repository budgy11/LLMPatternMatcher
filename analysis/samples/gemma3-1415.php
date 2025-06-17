
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process:
 * 1.  Checks if the email exists in the database.
 * 2.  Generates a unique token for the password reset request.
 * 3.  Stores the token and user ID in the database.
 * 4.  Sends an email with a link containing the token.
 *
 * @param string $email The email address to reset the password for.
 * @return bool True if the reset email was sent successfully, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Check if the email exists in the database
  $user = getUserById($email); // Assuming you have a function to get user by email
  if (!$user) {
    error_log("User with email $email not found."); // Log the error for debugging
    return false;
  }

  // 2. Generate a unique token
  $token = generate_unique_token();

  // 3. Store the token and user ID in the database
  $result = save_reset_token($user->id, $token);
  if (!$result) {
    error_log("Failed to save reset token for user $email");
    return false;
  }

  // 4. Send the reset email
  $reset_link = generate_reset_link($token);
  send_reset_password_email($user->email, $reset_link);

  return true;
}


/**
 * Dummy functions - Replace with your actual database and email implementation
 */

/**
 * Get user by email.  This is a placeholder - implement your database query.
 *
 * @param string $email The email address to search for.
 * @return object|null The user object if found, null otherwise.
 */
function getUserById(string $email) {
  // Replace this with your actual database query
  // Example using a mock database:
  $users = [
    ['id' => 1, 'email' => 'user1@example.com', 'password' => 'password123'],
    ['id' => 2, 'email' => 'user2@example.com', 'password' => 'anotherpassword'],
  ];
  foreach ($users as $user) {
    if ($user['email'] == $email) {
      return (object) ['id' => $user['id'], 'email' => $user['email']];
    }
  }
  return null;
}


/**
 * Generate a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token() {
  return bin2hex(random_bytes(32));
}


/**
 * Save the reset token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The reset token.
 * @return bool True if saved successfully, false otherwise.
 */
function save_reset_token(int $userId, string $token) {
  // Replace with your database code to store the token
  // Example (using a mock database):
  $tokens = [
    ['userId' => 1, 'token' => 'xyz123', 'expiry' => time() + 3600] // Token expires in 1 hour
  ];
  // Simulate database insertion
  $result = true; // Replace with your actual database query
  return $result;
}

/**
 * Generate the reset password link.
 *
 * @param string $token The reset token.
 * @return string The reset password link.
 */
function generate_reset_link(string $token) {
    return 'http://yourwebsite.com/reset-password?token=' . urlencode($token);
}


/**
 * Send the reset password email.
 *
 * @param string $email The email address to send the email to.
 * @param string $resetLink The reset password link.
 */
function send_reset_password_email(string $email, string $resetLink) {
  // Replace with your email sending logic
  // Example:
  error_log("Sending reset password email to: " . $email . " with link: " . $resetLink);  // Logging for testing
}


// Example usage (replace with actual input):
// if (isset($_POST['email'])) {
//   $email = $_POST['email'];
//   if (forgot_password($email)) {
//     echo "Reset password email has been sent to $email.";
//   } else {
//     echo "Failed to send reset password email. Please try again.";
//   }
// }
?>
