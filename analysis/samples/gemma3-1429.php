

<?php

// Assume you have a database connection established (e.g., $conn)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password(string $email)
{
  global $conn; // Make the database connection accessible within the function

  // 1. Check if the email exists
  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email); // "s" indicates a string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // User doesn't exist
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $userEmail = $user['email'];


  // 2. Generate a unique token (e.g., UUID)
  $resetToken = bin2hex(random_bytes(32));

  // 3. Store the token and user ID in the database
  $query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("siss", $userId, $resetToken, date("Y-m-d H:i:s"));  // 's' for string, 'i' for integer
  if ($stmt->execute()) {
    $stmt->close();
    // 4.  Send an email with a reset link
    $resetLink = $_SERVER['REQUEST_SCHEME'] . "://".$_SERVER['HTTP_HOST']."/reset_password?token=" . $resetToken;
    // Send an email to the user with the reset link.
    //  You'll need to implement your email sending logic here (e.g., using PHPMailer or similar)
    //  Example (Placeholder - replace with your actual email sending code):
    // send_email($userEmail, "Password Reset Link", "Click here to reset your password: " . $resetLink);

    return true; // Password reset initiated successfully
  } else {
    $stmt->close();
    return false; // Error during database insertion
  }
}


// Placeholder function to simulate sending an email. Replace with your actual email sending code.
function send_email($to, $subject, $message) {
    //  This is just a placeholder.  You *must* replace this with actual email sending code.
    //  e.g., using PHPMailer.
    error_log("Simulated email sent to: " . $to . " with subject: " . $subject . " and message: " . $message);
}


// Example Usage (for testing - remove from production code)
// echo forgot_password("testuser@example.com"); // Replace with a real email
?>
