

<?php

/**
 * Resets a user's password based on their email address.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @param array $dbConnection A database connection object.
 *
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password(string $email, string $newPassword, array $dbConnection) {
  // 1. Validate Input (Important for security!)
  if (empty($email) || empty($newPassword)) {
    return false; // Return immediately if input is invalid
  }

  // 2. Check if user exists
  $user = mysqli_query($dbConnection, "SELECT id, email FROM users WHERE email = '$email'");
  if (mysqli_num_rows($user) == 0) {
    return false; // User not found
  }

  $user_row = mysqli_fetch_assoc($user_row);
  $userId = $user_row->id;

  // 3. Hash the New Password
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the User's Password
  $result = mysqli_query($dbConnection, "UPDATE users SET password = '$hashedNewPassword' WHERE id = '$userId'");

  if (mysqli_affected_rows($dbConnection) > 0) {
    return true; // Password reset successful
  } else {
    return false; // Password update failed
  }
}


// --- Example Usage (Simulated Database - Replace with your actual database connection) ---

// Simulate a database connection (replace with your real connection)
$dbConnection = mysqli_connect("localhost", "your_username", "your_password", "your_database");

if (mysqli_connect_errno($dbConnection)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}


// Example data
$email = "testuser@example.com"; // Replace with a valid user email
$newPassword = "NewPassword123!"; // Replace with the new password you want to set

// Call the function
$resetResult = forgot_password($email, $newPassword, $dbConnection);

if ($resetResult) {
  echo "Password reset initiated successfully for $email.<br>";
} else {
  echo "Failed to reset password for $email.<br>";
}

// Close the connection (Important!)
mysqli_close($dbConnection);
?>
