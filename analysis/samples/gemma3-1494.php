
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// Assume you have a database connection established and a 'users' table with an 'email' column.

function forgot_password($email, $new_password, $token, $db_connection) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 2. Check if user exists
  $stmt = $db_connection->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // User doesn't exist
  }

  // 3. Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 4. Update the user's password
  $stmt = $db_connection->prepare("UPDATE users SET password = ? WHERE email = ?");
  $stmt->execute([$hashed_password, $email]);

  // 5.  Token is typically stored in the database and is invalidated after use.
  //     In a real application, you'd store the token in the database along with the user record.
  //     For simplicity, we're just checking for its existence here.
  // Example (This part would need to be modified based on your token storage)
  $stmt = $db_connection->prepare("SELECT id FROM reset_tokens WHERE user_id = ? AND token = ?");
  $stmt->execute([$user['id'], $token]);
  $reset_token_count = $stmt->rowCount();

  if ($reset_token_count == 0) {
     // Token doesn't exist, likely a bad request.
     return false;
  }
  
  //  If the token is valid, you could invalidate it here.
  //  For example:  
  //  $stmt = $db_connection->prepare("DELETE FROM reset_tokens WHERE user_id = ? AND token = ?");
  //  $stmt->execute([$user['id'], $token]);


  return true;
}


// --- Example Usage (for demonstration purposes) ---

//  **IMPORTANT:**  Replace these placeholders with your actual database connection.
$db_connection = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

// Example Email and New Password (FOR TESTING ONLY - NEVER DO THIS IN PRODUCTION)
$email = "test@example.com";
$new_password = "new_secure_password";
$token = "some_unique_token"; // Generate a random token (e.g., using uniqid())

if (forgot_password($email, $new_password, $token, $db_connection)) {
  echo "Password reset successful! Check your email for instructions.";
} else {
  echo "Password reset failed.  Please try again.";
}

// Close the database connection
$db_connection = null;
?>
