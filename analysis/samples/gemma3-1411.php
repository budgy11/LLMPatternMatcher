

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'password' column.  This is a simplified example - you'll need
// to adapt this to your specific database setup.

function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Retrieve User Data
  $stmt = $conn->prepare("SELECT id, password, salt FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return "User not found.";
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 3. Generate a Unique Token (for security)
  $token = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator

  // 4.  Store the Token and User ID in a temporary table (for security)
  // This is a temporary solution.  Consider more robust session-based or
  // email-based methods for production.  A temporary table is preferred
  // over a session for improved security and reliability.
  $stmt = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expiry_time) 
                           VALUES (?, ?, NOW())");
  $stmt->bind_param("sss", $user['id'], $token, $user['id']); // using user id for expiry time
  $stmt->execute();
  $stmt->close();


  // 5.  Send Password Reset Email (Example)
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click the following link to reset your password: " . 
             $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . $token;
  $headers = 'From: yourdomain@example.com' . "\r
";

  mail($to, $subject, $message, $headers);

  return "Password reset link sent to your email address.";
}


// Example usage (within a form or other context)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"]; // Assuming you have an email field in your form

  if (isset($email)) {
    $result = forgot_password($email);
    echo $result; // Display the result (e.g., "Password reset link sent...")
  }
}
?>
