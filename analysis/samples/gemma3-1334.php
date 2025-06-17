
</body>
</html>


<?php

// Assuming you have a database connection established and defined as $db

// Function to handle forgot password requests
function forgot_password($email) {
  // 1. Validate email format (basic check - improve for production)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $user = getUserByEmail($email); // Assuming you have a getUserByEmail function

  if (!$user) {
    return "User not found.";
  }

  // 3. Generate a unique token
  $token = generate_unique_token();

  // 4. Store the token and expiry time in the database
  //  - This is crucial for security.  Don't store the actual password!
  //  - Use a hashed token
  store_token($user['id'], $token, time() + 3600); // Store for 1 hour (3600 seconds)

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Click on the following link to reset your password: <a href='" . generate_reset_link($user['id'], $token) . "'>Reset Password</a>";
  $headers = "From: your_website@example.com\r
"; // Replace with your email address
  
  mail($email, $subject, $message, $headers);

  return "Password reset email sent.  Please check your inbox.";
}

// -------------------------------------------------------------------
// Helper functions (replace with your actual implementation)
// -------------------------------------------------------------------

// Dummy function to get user by email - REPLACE WITH YOUR DATABASE QUERY
function getUserByEmail($email) {
  // Replace this with your actual database query to fetch user by email
  // Example using a placeholder (replace with your actual database connection)
  $users = [
    ['id' => 1, 'email' => 'user1@example.com', 'password' => 'password123'],
    ['id' => 2, 'email' => 'user2@example.com', 'password' => 'anotherpass']
  ];

  foreach ($users as $user) {
    if ($user['email'] == $email) {
      return $user;
    }
  }
  return null; // User not found
}

// Dummy function to generate a unique token - IMPROVE FOR PRODUCTION!
function generate_unique_token() {
  return bin2hex(random_bytes(32)); // Use a cryptographically secure random source
}

// Dummy function to store the token in the database - REPLACE WITH YOUR DATABASE CODE
function store_token($user_id, $token, $expiry_time) {
  // Replace this with your actual database code to store the token and expiry
  // This is a simplified example - use prepared statements to prevent SQL injection
  // Example (using MySQLi - adapt to your database library)
  // $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiry_time) VALUES (?, ?, ?)");
  // $stmt->bind_param("sisi", $user_id, $token, $expiry_time);
  // $result = $stmt->execute();
  // ... handle the result
}

// Dummy function to generate the reset link - REPLACE WITH YOUR DATABASE CODE
function generate_reset_link($user_id) {
  // This should generate a URL to a page where the user can enter the new password.
  // The URL should include the user ID and the token.
  // For example:  /reset_password.php?id=[user_id]&token=[token]
  return "/reset_password.php?id=" . $user_id;
}

// -------------------------------------------------------------------
// Example Usage (for testing)
// -------------------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $response = forgot_password($email);
  echo "<p>" . $response . "</p>";
}
?>
