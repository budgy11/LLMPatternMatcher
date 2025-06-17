

<?php

// Ensure this is running on the server-side (e.g., a web server)
//  This code is for demonstration purposes and should be adapted for a
//  production environment with proper security measures.

//  Important:  NEVER store passwords directly in your code or database.
//  Always hash passwords using a strong hashing algorithm like bcrypt.

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if a password reset email was sent successfully, false otherwise.
 */
function forgotPassword($email) {
  // Validate the email (basic check - improve this for production)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 1. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32));

  // 2. Store the token in the database associated with the user's email.
  //    This is the most crucial step - adapt this part to your database schema.
  //    This example assumes a 'users' table with an 'email' and 'password_reset_token' column.
  //    You'll likely need to adjust this based on your application's database structure.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); //Replace placeholders
  $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    // User exists - proceed to store the token
    $stmt = $db->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry WHERE email = :email");
    $expiry = date('Y-m-d H:i:s', time() + 3600); // Expires in 1 hour (adjust as needed)
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    return $stmt->execute();
  } else {
    // User does not exist -  (You might want to handle this differently, e.g., create a new user)
    return false;
  }

  // 3. Send a password reset email
  $resetLink = 'http://yourwebsite.com/reset-password?token=' . $token; // Replace your website URL
  $subject = "Password Reset Request";
  $message = "Click the link above to reset your password: " . $resetLink;
  $headers = "From: yourwebsite@example.com\r
";
  mail($email, $message, $headers);  // Using PHP's mail() function (simple, but not always reliable)

  // You might want to use a more robust email sending library for production
  // (e.g., SwiftMailer, PHPMailer).

  return true;
}


// Example Usage (for testing - do NOT expose this in a real web application)
// $email = 'test@example.com'; // Replace with a valid email address
// if (forgotPassword($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email to " . $email;
// }

?>
