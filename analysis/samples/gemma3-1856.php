

<?php

// Assuming you have a database connection established (e.g., $pdo)

/**
 * Resets the user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword) {
  // 1. Validate Inputs (Important for security!)
  if (empty($email) || empty($newPassword)) {
    return false; // Or throw an exception: throw new InvalidArgumentException("Email and password are required.");
  }

  // 2. Check if the email exists in the database.
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user === false) {
    return false; // Email not found
  }

  // 3.  Hash the new password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the user's password in the database
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
  $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
  $stmt->execute();


  return true; // Password reset successful
}

// Example Usage:
// In your form, after the user submits the form...
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["newPassword"];

  if (forgotPassword($email, $newPassword)) {
    echo "Password reset email sent.  Check your inbox!";
  } else {
    echo "Error: Password reset failed. Please try again.";
  }
}
*/


// Example:  Illustrative only -  DO NOT put the actual password generation code in your app!
// In a real application, you would send an email with a link to a password reset form.
// This example shows how to generate a temporary password for testing.
function generateTemporaryPassword() {
  return bin2hex(random_bytes(16)); // Creates a strong random string
}
?>
