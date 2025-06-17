

<?php

// Assuming you have a database connection established (e.g., $pdo)
// and a table named 'users' with columns 'email', 'id', etc.

function forgotPassword($email, $pdo) {
  // 1. Check if the email exists in the database
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    // Email doesn't exist
    return false;
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generates a strong random token

  // 3. Create a token reset record in the database
  $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
  $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
  $stmt->bindParam(':token', $token, PDO::PARAM_STR);
  $expires_at = date('Y-m-d H:i:s', time() + (24 * 60 * 60)); // Token expires after 24 hours
  $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_STR);
  $stmt->execute();

  // 4. Send the reset email
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click the following link to reset your password: " .  urlencode("http://yourwebsite.com/reset_password?token=$token") . " This link will expire in 24 hours.";  // Ensure URL encoding for special characters

  $headers = "From: yourwebsite@example.com";  // Replace with your email address

  mail($to, $subject, $message, $headers);

  // 5. Return true to indicate success
  return true;
}

// Example usage:
// $email = $_POST['email'];  // Get email from form submission

// if (isset($email)) {
//   if (forgotPassword($email, $pdo)) {
//     echo "Password reset email has been sent to $email.";
//   } else {
//     echo "Invalid email address or user not found.";
//   }
// }
?>
