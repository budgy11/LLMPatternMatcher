

<?php

// Assume you have a database connection established and defined as $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password to set.
 * @return bool True if password reset successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // 2.  Hash the new password (Important for security!)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Update the password in the database
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$hashedNewPassword, $user['id']]);

  // 4. Return true if the update was successful
  return true;
}

// --- Example Usage (Demonstration) ---
// In a real application, this would be triggered by a form submission.
// For demonstration, let's hardcode the email and password.
// WARNING: This is for demonstration ONLY.  Never do this in a production environment!

// Simulate a database connection (replace with your actual connection)
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

// Example 1: Successful password reset
$email = 'testuser@example.com'; // Replace with a valid email
$newPassword = 'NewPassword123!';

if (forgotPassword($email, $newPassword)) {
  echo "Password reset successful for $email
";
} else {
  echo "Password reset failed for $email
";
}

// Example 2: Handling an invalid email
$invalidEmail = 'nonexistent@example.com';
$newPassword = 'AnotherPassword';

if (forgotPassword($invalidEmail, $newPassword)) {
    echo "Password reset successful for $invalidEmail
";
} else {
    echo "Password reset failed for $invalidEmail
";
}
?>
