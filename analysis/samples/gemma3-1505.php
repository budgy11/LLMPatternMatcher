

<?php

// Assuming you have a database connection established
// For example:
// $db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");


/**
 * Forgets a user's password by sending a password reset link to their email.
 *
 * @param string $email The email address of the user.
 * @return bool True if the reset link was generated successfully, false otherwise.
 */
function forgotPassword($email) {
  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided for password reset."); // Log for debugging
    return false;
  }

  // Check if the user exists
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    error_log("User with email '$email' not found."); // Log for debugging
    return false;
  }

  // Generate a unique token
  $token = bin2hex(random_bytes(32));

  // Hash the token (important for security)
  $hashedToken = password_hash($token, PASSWORD_DEFAULT);

  // Store the token in the database, associated with the user
  $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
  $stmt->execute([$hashedToken, time() + 3600, $email]); // expiry in 1 hour

  // Send an email with the reset link
  $subject = "Password Reset Link";
  $message = "Click on the following link to reset your password: " . "<a href='" . base_url . "reset_password?token=" . $token . "'>" . base_url . "reset_password?token=" . $token . "</a>";  // Replace base_url with your application's URL
  $headers = "From: Your Website <your_email@example.com>";

  mail($email, $message, $headers);

  return true;
}

// Example Usage (within a controller or function):
// $email = $_POST['email'];
// if (forgotPassword($email)) {
//   echo "Password reset email has been sent to $email";
// } else {
//   echo "Failed to generate password reset link.";
// }

?>
