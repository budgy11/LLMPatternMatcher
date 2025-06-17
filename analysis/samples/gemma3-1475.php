

<?php

// Assuming you have a database connection established (e.g., $db)
// This is a simplified example and should be adapted to your specific setup.
// Consider security implications and using prepared statements.

function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Stronger than md5
  // Store the token and user ID in the database
  // (You'll need to adapt this to your database schema)
  // Example using a hypothetical 'users' table and 'reset_tokens' table:

  // $sql = "INSERT INTO reset_tokens (user_id, token, expires_at) 
  //         VALUES (?, ?, NOW())";
  // $stmt = $db->prepare($sql);
  // $stmt->bind_param("s", $user_id, $token); 
  // $stmt->execute();
  // $stmt->close();


  // 3. Send an email with a reset link
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Please click on the following link to reset your password: " .
             "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>Reset Password</a>";
  $headers = "From: yourwebsite@example.com"; // Replace with your email address

  if (mail($to, $subject, $message, $headers)) {
    return true; // Reset link sent successfully
  } else {
    return "Failed to send reset email.";
  }
}



// Example usage (for testing -  DO NOT directly use this in production without proper security)
// $email = "test@example.com";
// $result = forgotPassword($email);
// echo $result; // Output:  "Reset link sent successfully"  or "Failed to send reset email"
?>
