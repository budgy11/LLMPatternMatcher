    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email">
    <button type="submit">Reset Password</button>
  </form>

  <p>Don't have an account? <a href="register.php">Sign Up</a></p>  <!-- Link to registration page -->


</body>
</html>


<?php

/**
 * Forgets the password for a user.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set for the user.
 * @param PDO $pdo The PDO database connection object.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo) {
  // 1. Sanitize and Validate Inputs (CRITICAL SECURITY STEP)
  $email = trim($email); // Remove leading/trailing whitespace
  $newPassword = trim($newPassword);

  if (empty($email) || empty($newPassword)) {
    return false; // Required fields are missing
  }

  // Hash the new password before storing it
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 2. SQL Query
  $sql = "UPDATE users SET password = :password, last_password_reset = NOW() WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':password', $hashedNewPassword);
  $stmt->bindParam(':email', $email);

  // 3. Execute the Query
  if ($stmt->execute()) {
    return true; // Password reset successful
  } else {
    // Handle query errors (e.g., user not found)
    error_log("Error resetting password for user: " . $email); // Log the error for debugging
    return false;
  }
}

// Example Usage (Illustrative - Replace with your actual database connection)
//  Important: This is just an example.  You *MUST* adapt it
//  to your specific database structure and connection details.

//  This example assumes you have a 'users' table with columns:
//  - id (int, primary key)
//  - email (string, UNIQUE)
//  - password (string, hashed)
//  - last_password_reset (datetime)


//  Replace this with your actual database connection
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_database_user';
$dbPass = 'your_database_password';

try {
  $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Example user details
$userEmail = 'testuser@example.com'; // Replace with the user's email
$newPassword = 'NewSecurePassword123';   // Replace with a new password

// Call the function to reset the password
if (forgotPassword($userEmail, $newPassword, $pdo)) {
  echo "Password reset successfully for user: " . $userEmail . "
";
} else {
  echo "Password reset failed for user: " . $userEmail . "
";
}

// Close the database connection (Good practice)
$pdo = null;
?>
