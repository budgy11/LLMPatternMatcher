

<?php

/**
 * Resets a user's password after a forgotten password request.
 *
 * @param string $email The email address of the user requesting the reset.
 * @param string $password The new password for the user.
 * @param string $new_password_confirmation The confirmation of the new password.
 * @return bool True on success, false on failure.
 */
function resetPassword(string $email, string $password, string $new_password_confirmation) {
  // 1. Input Validation and Sanitization - VERY IMPORTANT
  if (empty($email) || empty($password) || empty($new_password_confirmation)) {
    return false; // Require all fields
  }

  // Basic email validation (you can make this more robust)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Password complexity requirements (Example - Customize as needed)
  if (strlen($password) < 8) {
    return false; // Minimum password length
  }

  if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]*?\/<>\.])/', $password)) {
    return false; // Must contain at least one lowercase letter, uppercase letter, number, and special character
  }


  // 2. Database Interaction (Example - Adapt to your database)
  // Assuming you have a 'users' table with 'id', 'email', and 'password' columns

  try {
    // Connect to your database
    // $db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

    // Prepare the SQL query
    // $stmt = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
    // $stmt->bindParam(':password', $password);
    // $stmt->bindParam(':email', $email);

    // Execute the query
    // $result = $stmt->execute();

    // Check for errors
    // if ($result) {
    //   // Reset password successful
    //   return true;
    // } else {
    //   // Handle database error - logging is crucial here!
    //   error_log("Error resetting password for " . $email . ": " . print_r($db->errorInfo(), true));
    //   return false;
    // }

  } catch (PDOException $e) {
    // Handle database connection or query errors - Logging is crucial here!
    error_log("Database error resetting password for " . $email . ": " . $e->getMessage());
    return false;
  }
}


/**
 * Generates a unique, secure reset token.
 *
 * @return string A unique reset token.
 */
function generateResetToken() {
    return bin2hex(random_bytes(32));
}



/**
 * Example Usage (for testing purposes - not part of the core function)
 */

//  Example usage - NOT recommended for production
//  This is just for demonstration and testing.  Don't use this directly in a web application.

// $email = "test@example.com";
// $newPassword = "MyNewPassword123!";
// $token = generateResetToken();

// if (resetPassword($email, $newPassword, $newPassword)) {
//   echo "Password reset successful for " . $email . "!  Check your email for instructions.";
// } else {
//   echo "Password reset failed for " . $email . ".  Please try again.";
// }
?>
