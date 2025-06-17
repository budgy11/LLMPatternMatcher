

<?php

/**
 * Forgets a user's password.  This is a basic implementation and 
 * should be thoroughly reviewed and secured in a production environment.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password the user wants to set.
 * @param string $dbHost  The database hostname.
 * @param string $dbName  The database name.
 * @param string $dbUser  The database username.
 * @param string $dbPassword The database password.
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUser, string $dbPassword) {
  // 1. Validate input (basic - expand this!)
  if (empty($email) || empty($newPassword)) {
    return false;
  }

  // 2. Connect to the database (using PDO - recommended)
  try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Error handling
  } catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());  // Log the error
    return false;
  }

  // 3. Check if the user exists
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // User not found
  }

  // 4. Hash the new password (IMPORTANT - don't store passwords in plain text!)
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 5. Update the user's password in the database
  try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $user['id']]);
  } catch (PDOException $e) {
    error_log("Database update failed: " . $e->getMessage());
    return false;
  }

  return true;
}


// Example Usage (For testing - DO NOT USE IN PRODUCTION WITHOUT SECURING)
// Ensure you have a "users" table in your database with an "email" and "password" column.
// The "password" column should initially be empty or contain a placeholder value.

// $email = "testuser@example.com";
// $newPassword = "MyNewPassword123";
// $dbHost = "localhost";
// $dbName = "your_database_name";
// $dbUser = "your_database_username";
// $dbPassword = "your_database_password";

// if (forgotPassword($email, $newPassword, $dbHost, $dbName, $dbUser, $dbPassword)) {
//   echo "Password reset successful for " . $email . "
";
// } else {
//   echo "Password reset failed for " . $email . "
";
// }

?>
