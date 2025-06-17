

<?php

// IMPORTANT: This is a simplified example and should be adapted for production use.
// It's crucial to implement security best practices like:
// - Strong password hashing (bcrypt, Argon2)
// - Rate limiting
// - Email verification
// - Anti-spam measures
// - Logging and monitoring

// Assume you have a database connection established
// Example: $db = new mysqli("localhost", "username", "password", "database_name");

// Function to handle password reset
function forgotPassword($email, $newPassword) {
  // 1. Validate Inputs
  if (empty($email) || empty($newPassword)) {
    return "Error: Email and new password are required.";
  }

  // 2. Database Interaction (Simulated)
  // Replace this with your actual database query
  $email = filter_var($email, FILTER_SANITIZE_EMAIL); //Sanitize email for security

  // Check if the email exists in the database
  // $result = $db->query("SELECT id FROM users WHERE email = '$email'");
  // if ($result->num_rows > 0) {
  //    // User exists - proceed to reset password
  // } else {
  //    return "Error: Email address not found.";
  // }


  //  Simulated Database
  $users = [
      ['email' => 'test@example.com', 'id' => 1],
      ['email' => 'another@example.com', 'id' => 2],
  ];
  $user = null;
  foreach ($users as $u) {
    if ($u['email'] == $email) {
      $user = $u;
      break;
    }
  }

  if ($user === null) {
      return "Error: Email address not found.";
  }


  // 3. Generate a Reset Token (IMPORTANT - Use a strong method like UUID)
  $resetToken = bin2hex(random_bytes(32)); // Use a better method than this for production

  // 4. Store the Token in the Database (associate with the user)
  //  Example:
  //  $db->query("UPDATE users SET reset_token = '$resetToken', reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE email = '$email'");


  //Simulated storage - Replace with your database interaction
  $user['reset_token'] = $resetToken;
  $user['reset_token_expiry'] = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

  //5.  Create a link to the reset password page
  $resetLink = "https://yourwebsite.com/reset-password?token=$resetToken&user_id=$user['id']";

  // 6. Send the Reset Email
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Click the link below to reset your password:
" . $resetLink;
  $headers = "From: yourwebsite@example.com\r
";
  mail($to, $subject, $message, $headers);


  return "Password reset link sent to $email.";
}

// Example Usage (Simulated)
// $result = forgotPassword("test@example.com", "NewPassword123");
// echo $result;

?>
