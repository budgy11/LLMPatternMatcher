

<?php

// Database connection details (replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUsername = 'your_username';
$dbPassword = 'your_password';

// Error reporting (optional, but helpful for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * Resets a user's password based on their email.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword($email) {
  // 1. Validate the email address (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2. Check if the user exists
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if ($conn->connect_error) {
    error_log("Failed to connect to database: " . $conn->connect_error); // Log the error
    return false;
  }

  $sql = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);  // 's' indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // User not found
  }

  $user = $result->fetch_assoc();
  $user_id = $user['id'];
  $user_email = $user['email'];
  $stmt->close();

  // 3. Generate a unique token (important for security)
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

  // 4.  Create a password reset token record in the database
  $sql_token = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, NOW())";
  $stmt_token = $conn->prepare($sql_token);
  $stmt_token->bind_param("is", $user_id, $token);
  $stmt_token->execute();
  $stmt_token->close();


  // 5.  Send an email with the reset link
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "'>" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "</a>";
  $headers = "From: your_email@example.com";  // Replace with your email address

  mail($to, $message, $headers);

  // 6. Close the database connection
  $conn->close();

  return true;
}



//  Example Usage (for testing -  remove or comment out in production)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  if (isset($email) && !empty($email)) {
    if (forgotPassword($email)) {
      echo "Password reset email has been sent to " . $email;
    } else {
      echo "Error: Could not reset password.  User not found or email validation failed.";
    }
  } else {
    echo "Error: Please enter your email address.";
  }
}
?>
