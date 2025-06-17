    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// IMPORTANT: This is a simplified example for demonstration purposes.
// In a real-world scenario, you should *never* store passwords in plain text.
// Use a strong hashing algorithm like bcrypt or Argon2.

// Assume you have a database connection established (e.g., $db)
// and a user table with a 'email' column.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return ['status' => 'error', 'message' => 'Invalid email address.'];
  }

  // 2. Check if the user exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows === 0) {
    return ['status' => 'error', 'message' => 'User not found.'];
  }

  $user = $result->fetch_assoc();

  // 3. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 4. Store the token and user ID in the database
  $db->query("UPDATE users SET password_reset_token = '$token' WHERE email = '$email'");

  // 5. Send an email to the user with a link containing the token
  //    (This is where you would integrate with an email sending service.)
  //    For example:
  //    $subject = 'Password Reset Link';
  //    $message = "Please click the link below to reset your password: " . $base_url . "/reset_password?token=$token";
  //    $headers = 'From: your_email@example.com' . "\r
";
  //    $result = mail($email, $subject, $message, $headers);

  // 6. Return a success response
  return ['status' => 'success', 'message' => 'Password reset link sent to your email.'];
}


// Example Usage (Demonstration - REPLACE with actual database interaction)
//  --  This is just a placeholder to demonstrate the function.
//  --  In a real application, you'll use a database connection.

// Mock Database Connection (For demonstration only)
class MockDatabase {
    public function query($sql) {
        // Simulate query results for testing.  Replace with actual DB interaction.
        if (strpos($sql, "SELECT") === 0) {
            if (strpos($sql, "users WHERE email = 'test@example.com'") === 0) {
                return new \stdClass();
            }
        }
        return new \stdClass();
    }
}


$db = new MockDatabase(); // Using a mock database

$email = 'test@example.com';

$result = forgot_password($email, $db);

if ($result['status'] === 'success') {
  echo "Password reset link sent to $email.
";
} else {
  echo "Error: " . $result['message'] . "
";
}
?>
