

<?php

// This is a simplified example.  For production use,
// you'll want to use a more robust solution involving:
// - Storing passwords securely (hashed and salted)
// - Sending emails securely (HTTPS)
// - Input validation and sanitization
// - Preventing brute-force attacks (rate limiting, CAPTCHAs)
// - Logging all password reset attempts

// Configuration (replace with your actual settings)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset process started, false otherwise.
 */
function forgot_password($email)
{
  // 1. Validate Input
  if (empty($email)) {
    return false; // Or throw an exception
  }

  // 2. Check if User Exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error); // Log the error
    return false;
  }

  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email); // 's' indicates a string parameter

  if (!$stmt->execute()) {
    error_log("Query execution failed: " . $stmt->error); // Log the error
    $stmt->close();
    $conn->close();
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    error_log("User with email '$email' not found."); // Log the not found message
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();
  $conn->close();

  // 3. Generate a Unique Token (for security)
  $reset_token = bin2hex(random_bytes(32)); // Generate a random token

  // 4. Store Token in Database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  $query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sii", $userId, $reset_token, $conn->insert_id);  //'s' for string, 'i' for int

  if (!$stmt->execute()) {
    error_log("Query execution failed: " . $stmt->error);
    $stmt->close();
    $conn->close();
    return false;
  }
  $stmt->close();
  $conn->close();

  // 5. Send Password Reset Email
  $email_content = "Please click on the following link to reset your password: ";
  $reset_url = "https://yourdomain.com/reset_password?token=$reset_token"; // Replace with your URL
  $email_content .= $reset_url;

  // You'll need to implement your email sending logic here.
  // Example (replace with your actual email sending function):
  // send_email($user['email'], 'Password Reset', $email_content);

  // 6. Return True (success)
  return true;
}


// Example Usage (Replace with actual email sending)
//if (isset($_POST['email'])) {
//  $email = $_POST['email'];
//  if (forgot_password($email)) {
//    echo "Password reset email has been sent to $email.";
//  } else {
//    echo "Password reset failed.";
//  }
//}

// Sample function to simulate sending an email.  Replace with your real email sending logic.
function send_email($to, $subject, $body) {
    // Replace this with actual email sending code
    error_log("Simulating sending email to: " . $to . " with subject: " . $subject . " and body: " . $body);
}

?>
