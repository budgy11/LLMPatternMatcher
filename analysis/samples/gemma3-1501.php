

<?php

// Assuming you have a database connection established (e.g., $db_connection)
// and that you have a table named 'users' with a 'email' column.

function forgot_password($email, $db_connection) {
  // 1. Validate Input (Crucial for security)
  if (empty($email)) {
    return "Error: Email address cannot be empty.";
  }

  // Validate email format (basic check - more robust validation is recommended)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email address format.";
  }

  // 2. Check if User Exists
  $stmt = $db_connection->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);  // or MySQLi->fetch_assoc() if using MySQLi

  if (!$user) {
    return "Error: User not found.";
  }

  // 3. Generate a Token & Store it (Securely - consider hashing & salting)
  $token = bin2hex(random_bytes(32));  // Generate a random 32-byte token
  // For better security: hash and salt the token before storing it.
  // Example (using password_hash - best practice):
  // $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4. Prepare Password Reset Link
  $reset_link = "http://yourdomain.com/reset_password?token=" . urlencode($token);

  // 5. Store the Token (Important: Store this securely! Don't just in-memory)
  //  This example demonstrates storing in a database.
  $stmt = $db_connection->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
  $stmt->execute([$user['id'], $token, time() + 3600]); // Store for 1 hour (adjust as needed)


  // 6. Return the Reset Link
  return $reset_link;
}



// Example Usage (inside a form/controller)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_link = forgot_password($email, $db_connection); // Replace $db_connection with your connection

  if (strpos($reset_link, "Error:") === 0) {
    echo "<p style='color:red;'>" . $reset_link . "</p>";
  } else {
    echo "<p>Please check your email.  We have sent you a password reset link:</p>";
    echo "<a href='" . $reset_link . "'>Reset Password</a>";
  }
}
?>
