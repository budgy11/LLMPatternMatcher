

<?php

// Assuming you have a database connection established (e.g., $db)
// This is a simplified example and should be adapted to your specific setup.

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email (Important!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generates a secure, random token

  // 3. Hash the Token (Security!)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4. Store the Token and Email in the Database (For security, avoid storing plain passwords)
  //    In a real application, you would:
  //    - Create a new record in a 'forgot_password_tokens' table
  //    - The table columns would be:  email, token, created_at

  //  Simulated database insertion for demonstration
  //  Replace this with your actual database query
  $query = "INSERT INTO forgot_password_tokens (email, token, created_at) VALUES ('$email', '$token', NOW())";

  // Assuming you have a database connection object $db
  // $db->query($query); // Replace with your database query function

  // Simulate success.  Replace with your database interaction.
  $success = true;  //  Indicates whether the token was successfully stored

  // 5. Send the Reset Link (Email)
  $subject = "Password Reset Request";
  $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token' >Reset Password</a>";
  $headers = "From: your_email@example.com";  // Replace with your email address
  
  if (mail($email, $subject, $message, $headers)) {
    // 6. Return success message
    return "Password reset link has been sent to your email address. Check your inbox.";
  } else {
    // 7. Handle email sending failure
    return "Failed to send password reset email. Please try again.";
  }
}

// Example Usage (Illustrative)
// $email_to_reset = "user@example.com"; // Replace with the user's email
// $reset_message = forgot_password($email_to_reset);
// echo $reset_message;

?>
