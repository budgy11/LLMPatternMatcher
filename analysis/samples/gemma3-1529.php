

<?php

/**
 * Function to reset a user's password.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password for the user.
 * @param PDO $pdo The database connection object (PDO object).
 * @param string $table_name The name of the table containing user information (e.g., 'users').
 * @param string $email_column The column name for the email address (e.g., 'email').
 * @param string $password_column The column name for the password (e.g., 'password').
 *
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo, string $table_name = 'users', string $email_column = 'email', string $password_column = 'password')
{
  try {
    // 1. Check if the email exists
    $stmt = $pdo->prepare("SELECT id FROM " . $table_name . " WHERE " . $email_column . "= :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
      // Email not found
      return false;
    }

    // 2. Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 3. Update the password in the database
    $stmt = $pdo->prepare("UPDATE " . $table_name . " SET " . $password_column . " = :hashedPassword WHERE " . $email_column . "= :email");
    $stmt->bindParam(':hashedPassword', $hashedNewPassword, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      // Password updated successfully
      return true;
    } else {
      // Password update failed
      return false;
    }

  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage()); // Log the error for debugging
    return false;
  }
}

// Example Usage (Illustrative - requires a database connection)
// IMPORTANT:  This is a demonstration.  Don't directly use this in production
// without proper validation, security measures (CSRF protection, etc.) and logging.

// Dummy database connection (replace with your actual connection)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

$emailToReset = 'testuser@example.com'; // Replace with the user's email
$newPassword = 'NewSecurePassword123';

if (forgotPassword($emailToReset, $newPassword, $pdo)) {
  echo "Password reset successful.  Please check your email.";
} else {
  echo "Password reset failed.  Please try again.";
}

//Close the database connection (important to release resources)
$pdo = null;
?>
