

<?php

// IMPORTANT: This is a basic example.  For production environments,
// you MUST use a secure method for handling passwords and session management.
// This example is for educational purposes only and is NOT secure enough for real-world use.

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_pass = 'your_password';

// Function to reset the password
function forgotPassword($email, $new_password) {
  // 1. Connect to the database
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    // Handle database connection error (log the error, display an error message to the user)
    error_log("Database connection error: " . $e->getMessage());
    // Display an error message to the user (e.g., "Unable to connect to the database.")
    return false;
  }

  // 2. Check if the email exists in the 'users' table
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Email doesn't exist
    return false;
  }

  // 3. Hash the new password (VERY IMPORTANT - NEVER store passwords in plain text!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 4. Update the password for the user
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
  $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  if ($stmt->execute()) {
    return true;
  } else {
    // Handle update error (log the error)
    error_log("Error updating password: " . $stmt->errorInfo()[2]);
    return false;
  }
}

// Example Usage:  (This is for demonstration only - NEVER include this directly in a web form)
// $email = 'user@example.com';
// $new_password = 'NewSecurePassword123';

// if (forgotPassword($email, $new_password)) {
//   echo "Password reset successfully! Check your email.";
// } else {
//   echo "Failed to reset password. Please check your email address and try again.";
// }

?>
