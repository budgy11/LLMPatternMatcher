

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with columns 'id', 'email', and 'password'

// Function to handle the forgot password request
function forgot_password($email) {
  // 1. Validate the email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user) {
    return "User not found.";
  }

  // 3. Generate a unique token (e.g., using a UUID)
  $token = bin2hex(random_bytes(32)); // Generate a 32-byte (256-bit) random string

  // 4.  Create a token record in the database
  $stmt = $db->prepare("INSERT INTO password_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
  $stmt->execute([$user['id'], $token, time() + (24 * 60 * 60)]); // Expires in 24 hours
  $token_id = $db->lastInsertId();

  // 5.  Send an email to the user with a password reset link
  $reset_link = "https://yourwebsite.com/reset_password?token=$token"; // Replace with your domain

  $to = $email;
  $subject = "Password Reset";
  $message = "Click on the following link to reset your password: " . $reset_link;
  $headers = "From: Your Website <noreply@yourwebsite.com>";

  mail($to, $subject, $message, $headers);

  return "Password reset email has been sent to $email.";
}


// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgot_password($email);
  echo $result;
}
?>
