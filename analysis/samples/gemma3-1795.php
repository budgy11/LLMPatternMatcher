

<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $newPassword) {
  // 1. Validate input (very important!)
  if (empty($email) || empty($newPassword)) {
    return false; // Return false if either email or password is empty
  }

  // 2. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // 's' indicates a string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  // 3. Hash the new password (VERY IMPORTANT for security)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the user's password in the database
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
  $stmt->bind_param("ss", $hashedNewPassword, $email); // 's' indicates a string
  $exec_result = $stmt->execute();

  if ($exec_result) {
    $stmt->close();
    return true; // Password reset successful
  } else {
    // Handle errors:  Print the error message for debugging
    error_log("Error resetting password for user: " . $email . " - " . $db->error);  // Log the error
    return false;
  }
}

// Example Usage (For demonstration purposes - DO NOT USE THIS IN PRODUCTION)
// $emailToReset = "testuser@example.com";
// $newPass = "NewPassword123";

// if (forgot_password($emailToReset, $newPass)) {
//   echo "Password reset successful! Please check your email.";
// } else {
//   echo "Password reset failed.";
// }


?>
