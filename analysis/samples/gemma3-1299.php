    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email address">
    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established and a 'users' table
// with an 'email' column.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @return bool True if password reset was successful, false otherwise.
 */
function resetPassword(string $email, string $newPassword) {
  // 1. Validate Input (Important for security!)
  if (empty($email) || empty($newPassword)) {
    return false; // Required fields are missing
  }

  // Hash the new password (VERY IMPORTANT for security!)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);


  // 2. Database Query -  Adapt this to your specific database setup
  //  Replace 'your_database_connection' and 'users' with your actual values.
  try {
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Adjust DSN
    $stmt = $db->prepare("UPDATE users SET password = :password, last_password_reset = NOW() WHERE email = :email"); // Consider adding 'last_password_reset' column to track resets
    $stmt->bindParam(':password', $hashedNewPassword);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // 3.  Handle Result
    if ($stmt->rowCount() > 0) {
      return true;
    } else {
      return false; // User not found
    }

  } catch (PDOException $e) {
    // Handle database errors - Log the error for debugging!
    error_log("Database error resetting password: " . $e->getMessage());
    return false; //  Indicate failure (database error)
  }
}



// Example Usage (for demonstration purposes only - DO NOT expose this in a live application)
// In a real application, this would come from a form submission.

// Example: Reset password for user with email 'test@example.com' to 'newPassword123'
$emailToReset = 'test@example.com'; // Replace with the actual email
$newPassword = 'newPassword123';

if (resetPassword($emailToReset, $newPassword)) {
  echo "Password reset successfully for $emailToReset";
} else {
  echo "Failed to reset password for $emailToReset";
}
?>
