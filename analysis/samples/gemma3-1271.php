

<?php

// Assuming you have a database connection established (e.g., $db)
// And a 'users' table with a 'email' column

// Function to handle password reset requests
function forgotPassword($email) {
  // 1. Validate Email (Important for security!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the database
  $query = "SELECT id, password_reset_token, expiry_timestamp FROM users WHERE email = ?";
  $stmt = $db->prepare($query); // Assuming $db is your database connection
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "Email not found.";
  }

  // 3. Generate a unique password reset token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 4. Store the token in the database for this user
  $query = "INSERT INTO password_resets (user_id, token, expiry_timestamp) VALUES (?, ?, ?)";
  $stmt = $db->prepare($query);
  $stmt->execute([$user['id'], $token, time() + (60 * 60 * 24)]); // Token valid for 24 hours

  // 5. Send the password reset email (implementation omitted for brevity)
  //  - This would involve sending an email with a link containing the token
  //    (e.g.,  https://yourwebsite.com/reset_password.php?token=$token)
  //  -  Use a library or function for sending emails (e.g., PHPMailer)

  return "Password reset email sent.  Please check your inbox.";

}

// Example usage (for demonstration only - don't expose this in a production environment!)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $reset_message = forgotPassword($email);
  echo "<p>" . $reset_message . "</p>";
}
?>
