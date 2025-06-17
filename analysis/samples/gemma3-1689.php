        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $newPassword) {
  global $db; // Assuming $db is your database connection

  // 1.  Check if the email exists in the database.  Case-insensitive comparison is important.
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $email_lower = strtolower($email);  // Convert to lowercase for comparison
  $stmt->bind_param("s", $email_lower);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id'];
  $stmt->close();


  // 2.  Hash the new password before updating the database.  **CRITICAL SECURITY STEP**
  $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3.  Update the user's password in the database.
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("ss", $hashed_password, $user_id);
  $exec_result = $stmt->execute();

  if (!$exec_result) {
    // Handle database errors.  Important for debugging!
    error_log("Error updating password for user " . $email . ": " . $db->error);
    $stmt->close();
    return false;
  }

  $stmt->close();

  return true; // Password reset successful
}


// Example Usage (Illustrative -  This would be part of your form handling)

//  Assuming you have $email and $new_password from form input.

// $email = $_POST['email'];
// $newPassword = $_POST['new_password'];

// if (isset($email) && isset($newPassword)) {
//   if (forgot_password($email, $newPassword)) {
//     echo "Password reset email sent. Please check your inbox.";
//   } else {
//     echo "Error resetting password. Please try again.";
//   }
// } else {
//   echo "Invalid input. Please enter your email and a new password.";
// }

?>
