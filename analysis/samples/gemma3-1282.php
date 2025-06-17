

<?php

// Replace with your database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password($email) {
  // 1. Validate Input
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided."); // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); 

  // 3. Store the Token and User ID in a Temporary Table (For Security)
  //    This prevents users from guessing or guessing tokens.
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      error_log("User with email $email not found.");
      return false;
    }

    // Prepare to insert data
    $insert_stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expiry_timestamp) VALUES (:user_id, :token, :expiry)");
    $insert_stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
    $insert_stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $insert_stmt->bindParam(':expiry', time() + (24 * 60 * 60), PDO::PARAM_INT); // Token expires in 24 hours
    $insert_stmt->execute();

    // 4. Send the Reset Link
    $reset_link = "http://yourwebsite.com/reset_password?token=$token&user_id=$user['id']"; // Replace with your website URL
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $reset_link;
    $headers = "From: your_email@example.com\r
";

    if (mail("user@example.com", $message, $headers)) { // Replace with user's email
      return true;
    } else {
      error_log("Failed to send email for password reset.");
      // Optionally, you could delete the temporary record here to avoid unnecessary data
      // $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = :user_id AND token = :token");
      // $delete_stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
      // $delete_stmt->bindParam(':token', $token, PDO::PARAM_STR);
      // $delete_stmt->execute();
      return false;
    }

  } catch (PDOException $e) {
    error_log("PDO Error: " . $e->getMessage()); // Log the error
    return false;
  } finally {
    $pdo = null; // Close the connection
  }
}


// Example Usage (For Testing - Do not use this directly in a public-facing application)
// $email = "test@example.com";
// if (forgot_password($email)) {
//   echo "Password reset email has been sent to $email.";
// } else {
//   echo "Failed to reset password for $email.";
// }


?>
