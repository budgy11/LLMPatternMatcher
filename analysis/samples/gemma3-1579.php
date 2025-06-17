

<?php

// This is a simplified example.  In a real-world scenario, you'd
// store passwords securely (hashed) and use more robust security
// practices.

// IMPORTANT:  This example is for demonstration purposes ONLY.
// DO NOT use this in a production environment without proper security measures.

// Configuration (replace with your database credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$db_name = 'your_db_name';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  if ($conn->connect_error) {
    error_log("Failed to connect to database: " . $conn->connect_error); // Log the error
    return false;
  }

  // 2.  Find the user by email
  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email); // "s" indicates a string parameter

  if (!$stmt->execute()) {
    error_log("Failed to execute query: " . $stmt->error); // Log the error
    $stmt->close();
    $conn->close();
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    error_log("User not found with email: " . $email); // Log the error
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userID = $user['id'];

  // 3. Generate a unique token (e.g., UUID) -  Important for security
  $token = bin2hex(random_bytes(32));

  // 4.  Create a password reset token record in the database
  $query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sss", $userID, $token, $conn->insert_id); // 's' for string, 'i' for integer (for insert_id)

  if (!$stmt->execute()) {
    error_log("Failed to insert password reset token: " . $stmt->error);
    $stmt->close();
    $conn->close();
    return false;
  }

  // 5.  Send an email with a reset link
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click on the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset?token=" . $token;
  $headers = "From: your_email@example.com";

  // You would typically use a mail function or a service like SendGrid/Mailgun
  // to send the email.  This is just a placeholder.
  // Example:  mail($to, $subject, $message, $headers);

  // 6. Success
  $stmt->close();
  $conn->close();
  return true;
}


// Example Usage (replace with actual email)
$email = 'test@example.com'; // Replace with the user's email

if (forgot_password($email)) {
  echo "Password reset email sent to " . $email;
} else {
  echo "Failed to initiate password reset.";
}

?>
