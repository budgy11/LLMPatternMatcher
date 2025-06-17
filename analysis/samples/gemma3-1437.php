

<?php

// Database connection details (Replace with your actual values)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPass = 'your_password';

// Ensure you've created a 'users' table in your database
// with at least a 'email' column.  Example:
// CREATE TABLE users (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   email VARCHAR(255) NOT NULL UNIQUE,
//   password VARCHAR(255) NOT NULL
// );


/**
 * Resets a user's password using email.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email was sent, false otherwise.
 */
function forgot_password($email) {
  global $dbHost, $dbName, $dbUser, $dbPass;

  // Validate email (basic check - you can add more robust validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // Connect to the database
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  // Check connection
  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error); // Log the error for debugging
    return false;
  }

  // Prepare and execute the query to find the user
  $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // User not found
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $userName = $user['name'];
  $stmt->close();

  // Generate a unique token (e.g., using a cryptographically secure function)
  $resetToken = bin2hex(random_bytes(32)); // Generate a 32-byte (256-bit) random string

  // Prepare the reset token insertion query
  $insertStmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())");
  $insertStmt->bind_param("sss", $userId, $resetToken, $conn->insert_id);

  if (!$insertStmt->execute()) {
    error_log("Password reset token insertion failed: " . $insertStmt->error);
    $insertStmt->close();
    $conn->close();
    return false;
  }


  // Prepare and send the password reset email
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . urlencode($resetToken); // Replace yourdomain.com
  $headers = "From: your_email@example.com";

  if (mail($to, $subject, $message, $headers)) {
    // Successfully sent the email
    $conn->close();
    return true;
  } else {
    // Failed to send email
    error_log("Failed to send password reset email.");
    $conn->close();
    return false;
  }
}


// Example usage (from a form submission, for example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  if (forgot_password($email)) {
    echo "Password reset email has been sent to " . $email;
  } else {
    echo "Failed to send password reset email. Please check your email address.";
  }
}
?>
