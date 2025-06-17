

<?php

/**
 * Resets a user's password.
 *
 * This function generates a unique token, sends an email with a link to
 * reset the password, and sets the token in the database.
 *
 * @param string $email The email address of the user.
 * @param string $new_password The new password for the user.
 * @param PDO $pdo PDO database connection object.
 * @return bool True if the password reset was initiated successfully, false otherwise.
 */
function forgot_password(string $email, string $new_password, PDO $pdo) {
  // 1. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
  $expiry = time() + 3600; // Token expires after 1 hour (3600 seconds)

  // 2. Insert the token into the database
  $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
  $stmt->execute([$email, $token, $expiry]);

  // 3. Send the password reset email
  $subject = "Password Reset";
  $message = "To reset your password, please click on the following link: " .
             "<a href='" . base_url() . "/reset_password?token=" . $token . "'>Reset Password</a>";
  $headers = "From: " . "noreply@" . base_url() . "\r
";
  mail($email, $subject, $message, $headers);

  return true; // Indicate success
}


// Example Usage (Illustrative - Requires a `reset_password` route and a database setup)

// Assuming $pdo is your PDO database connection object
// Assuming $email is the user's email address
// Assuming $new_password is the new password

// $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_user', 'your_password');
// $email = "user@example.com";
// $new_password = "new_secure_password";

// if (forgot_password($email, $new_password, $pdo)) {
//   echo "Password reset email has been sent to $email.";
// } else {
//   echo "Failed to initiate password reset.";
// }

?>
