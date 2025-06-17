

<?php

// Replace with your actual database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was initiated, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Check if the email exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    error_log("Prepare statement failed: " . $conn->error);
    return false;
  }

  $email_to_check = $email;  // Pass the email to the prepared statement
  $stmt->bind_param("s", $email_to_check);

  if (!$stmt->execute()) {
    error_log("Execute statement failed: " . $stmt->error);
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email doesn't exist
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();

  // 2. Generate a unique reset token
  $reset_token = bin2hex(random_bytes(32)); // Use a secure random string

  // 3. Store the token in the database
  $conn->query("UPDATE users SET reset_token = ? WHERE id = ?", $reset_token, $userId);

  // 4.  Send the reset link (implementation is omitted for brevity)
  // This would typically involve sending an email with a link containing the token.

  // You would then have a link like:  https://yourwebsite.com/reset_password.php?token=$reset_token

  return true;
}

// Example usage (for testing - remove for production)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (isset($email) && !empty($email)) {
    $reset_success = forgot_password($email);

    if ($reset_success) {
      echo "<p>Password reset link has been sent to your email address.</p>";
      echo "<p>Please check your inbox.</p>";
    } else {
      echo "<p>An error occurred while attempting to reset your password.</p>";
      echo "<p>Please check your email address and try again.</p>";
    }
  } else {
    echo "<p>Please enter your email address.</p>";
  }
}
?>
