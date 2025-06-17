    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="Your email">
    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password($email) {
  // 1. Validate the email (basic check - can be enhanced)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 2.  Check if the user exists.
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  // Prepare the SQL query
  $sql = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    error_log("SQL prepare failed: " . $conn->error);
    return false;
  }

  // Bind the parameter
  $stmt->bind_param("s", $email);

  // Execute the query
  if (!$stmt->execute()) {
    error_log("Query failed: " . $stmt->error);
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();
  $conn->close();

  // 3. Generate a unique token and store it in the database
  $token = bin2hex(random_bytes(32)); // Generate a secure random token
  
  // Prepare and execute the SQL query to update the user record
  $sql = "UPDATE users SET password_reset_token = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    error_log("SQL prepare failed: " . $conn->error);
    return false;
  }

  $stmt->bind_param("ss", $token, $userId);

  if (!$stmt->execute()) {
    error_log("Query failed: " . $stmt->error);
    return false;
  }

  // 4.  Send an email to the user with a link to reset their password.
  //    This part is not included in this simplified example,
  //    but you would typically use an email library to send the email.

  // Return True to indicate success
  return true;
}

// Example usage (for testing - don't expose this in a production environment)
// $email = "testuser@example.com";  // Replace with a valid email
// if (forgot_password($email)) {
//   echo "Password reset link sent to $email";
// } else {
//   echo "Failed to reset password.";
// }

?>
