

<?php

// This is a simplified example for demonstration purposes.
// In a real application, you should *never* store passwords in plain text.
// Use a strong hashing algorithm like bcrypt or Argon2id.
// This code is for educational purposes only.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $db_connection A database connection object (must be established).
 *
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgot_password(string $email, string $newPassword, PDO $db_connection) {
  // Validate input (add more validation as needed)
  if (empty($email) || empty($newPassword)) {
    return false;
  }

  try {
    // Check if the email exists
    $stmt = $db_connection->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      return false; // User not found
    }

    // Update the user's password
    $stmt = $db_connection->prepare("UPDATE users SET password = :password, last_password_reset = NOW WHERE id = :user_id");
    $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    return true;

  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database error in forgot_password: " . $e->getMessage());
    return false;
  }
}


// Example Usage (Illustrative - Replace with your actual database connection)
// This section is just for demonstration.  Don't run this in a production environment
// without proper validation and security measures.
//
// Assuming you have a PDO database connection object named $pdo

/*
$email = "testuser@example.com";
$newPassword = "NewSecretPassword123";

if (forgot_password($email, $newPassword, $pdo)) {
  echo "Password reset successful.  Please check your email for instructions.";
} else {
  echo "Password reset failed.";
}
*/


//  Important Considerations and Security Best Practices

// 1.  Password Hashing:  **Never** store passwords in plain text. Use a robust hashing algorithm like bcrypt or Argon2id.  These algorithms create a one-way hash, making it computationally infeasible to reverse engineer the original password.

// 2.  Email Sending:  This example doesn't include email sending. You'll need to integrate a library or service to send an email containing a temporary password, a link to reset the password, or instructions. Be extremely careful with security when sending emails, especially if the email contains a temporary password.

// 3.  Temporary Password:  When resetting a password, use a temporary password.  Force the user to change it upon their first login.

// 4.  Session Management: After the user successfully changes their password, you'll need to manage their session and potentially invalidate the old session to prevent unauthorized access.

// 5.  Rate Limiting:  Implement rate limiting to prevent brute-force password reset attempts.

// 6.  Input Validation:  Thoroughly validate all user inputs (email, new password) to prevent SQL injection and other vulnerabilities.

// 7.  Database Security:  Secure your database connection and follow best practices for database security.

// 8.  Logging:  Log all password reset attempts, both successful and unsuccessful, for auditing and security monitoring.

?>
