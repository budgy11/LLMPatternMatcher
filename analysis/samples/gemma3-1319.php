

<?php

// Include the database connection file (assuming you have one)
require_once 'db_connection.php'; // Replace with your actual connection file

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was generated and sent, false otherwise.
 */
function forgot_password(string $email) : bool
{
  // Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // Hash the password (for security - crucial!)
  $hashed_password = password_hash("default_password", PASSWORD_DEFAULT); // Replace "default_password" with an actual, default password for testing

  // Generate a unique reset token
  $reset_token = bin2hex(random_bytes(32));  // Use a secure random bytes function

  // Store the token and user ID in the database
  $query = "INSERT INTO password_resets (user_id, token, created_at) 
             VALUES (:user_id, :token, NOW())";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':user_id', $email);
  $stmt->bindParam(':token', $reset_token);
  $stmt->execute();

  // Send the reset link email
  $subject = 'Password Reset Link';
  $message = "Please click the following link to reset your password: " .
             '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $reset_token . '">' .
             $_SERVER['PHP_SELF'] . '?reset=' . $reset_token . '</a>';
  $headers = 'From: webmaster@example.com' . "\r
"; // Replace with your email address

  if (mail($email, $subject, $message, $headers)) {
    return true;
  } else {
    // Handle email sending failure (log it, etc.)
    error_log("Failed to send password reset email to: " . $email);
    return false;
  }
}


/**
 * Verifies the reset token and updates the user's password.
 *
 * @param string $token The reset token.
 * @param string $new_password The new password.
 * @return bool True if the password was successfully updated, false otherwise.
 */
function verify_reset(string $token, string $new_password) : bool
{
  // Check if the token exists in the database
  $query = "SELECT user_id FROM password_resets WHERE token = ? LIMIT 1";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':token', $token, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    return false; // Token not found
  }

  $user_id = $result['user_id'];

  // Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Update the user's password in the database
  $query = "UPDATE users SET password = :password WHERE id = :user_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':password', $hashed_password);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  // Delete the reset token from the database
  $query = "DELETE FROM password_resets WHERE token = ? AND user_id = ?";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':token', $token, PDO::PARAM_STR);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  return true;
}



// Example Usage (For testing - this would typically be in a form submission)
//  $email = 'test@example.com';
//  if (forgot_password($email)) {
//    echo "Reset link sent to: " . $email;
//  } else {
//    echo "Failed to generate reset link.";
//  }


?>
