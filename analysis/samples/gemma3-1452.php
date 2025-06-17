    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @return bool True if password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword) {
  // 1. Check if the email exists
  $user = getUserByEmail($email);

  if (!$user) {
    return false; // User not found
  }

  // 2. Hash the new password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Update the user's password in the database
  $result = updateUserPassword($user->id, $hashedPassword);

  if ($result === false) {
    return false; // Database error
  }

  // 4. (Optional) Send an email notification
  // You'd replace this with your actual email sending logic.
  // This is just a placeholder.
  sendPasswordResetEmail($user->email, $hashedPassword);

  return true; // Password reset successful
}


/**
 * Helper function to get a user by email.  Replace this with your actual database query.
 *
 * @param string $email The email address to search for.
 * @return User|null A User object if found, null otherwise.
 */
function getUserByEmail(string $email): ?User {
  // Replace this with your actual database query (e.g., using PDO or MySQLi)
  // Example using a hypothetical User class:
  // return $db->query("SELECT * FROM users WHERE email = '$email' LIMIT 1")->fetch(PDO::FETCH_CLASS, 'User');

  // Placeholder - Replace with your actual database code
  $users = getAllUsers(); //  Assuming you have a function to get all users

  foreach ($users as $user) {
    if ($user->email === $email) {
      return $user;
    }
  }
  return null;
}



/**
 * Helper function to update a user's password. Replace this with your actual database update query.
 *
 * @param int $userId The ID of the user to update.
 * @param string $hashedPassword The hashed new password.
 * @return bool True if password update was successful, false otherwise.
 */
function updateUserPassword(int $userId, string $hashedPassword): bool {
  // Replace this with your actual database update query
  // Example using PDO:
  // try {
  //   $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
  //   $stmt->bindParam(':password', $hashedPassword);
  //   $stmt->bindParam(':id', $userId);
  //   return $stmt->execute();
  // } catch (Exception $e) {
  //   // Handle database errors here
  //   return false;
  // }

  // Placeholder
  return true; // Replace with your database update logic
}


/**
 * Placeholder function to send an email notification.  Implement your email sending logic here.
 *
 * @param string $email The recipient's email address.
 * @param string $hashedPassword The hashed password.
 * @return void
 */
function sendPasswordResetEmail(string $email, string $hashedPassword) {
  // This is a placeholder.  Implement your email sending logic here.
  // You'd use a library like PHPMailer to send emails.
  // Example:
  // $subject = 'Password Reset';
  // $body = "Please click the following link to reset your password: " . $resetLink;
  // mail($email, $subject, $body);

  echo "Password reset email sent (placeholder).
";
}

/**
 * Dummy User class for demonstration purposes.  Replace with your actual User class.
 */
class User {
  public string $email;
  public int $id;

  public function __construct(string $email, int $id) {
    $this->email = $email;
    $this->id = $id;
  }
}

/**
 * Dummy function to get all users. Replace this with your actual database retrieval logic.
 */
function getAllUsers(): array {
  // This is just a placeholder. Replace with your actual database retrieval logic.
  $users = [];
  $users[] = new User("test@example.com", 1);
  $users[] = new User("another@example.com", 2);
  return $users;
}



// Example Usage (for testing)
// WARNING: This is just an example. Don't use this directly in a production environment.
$email = "test@example.com"; // Replace with a valid user email.
$newPassword = "NewPassword123";

if (forgotPassword($email, $newPassword)) {
  echo "Password reset successfully for $email.
";
} else {
  echo "Password reset failed for $email.
";
}
?>
