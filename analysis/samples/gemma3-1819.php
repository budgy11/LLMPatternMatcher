

<?php

// Assuming you have a database connection established and a 'users' table with a 'email' column.

//  This is a basic example and should be adapted to your specific setup.
//  For production, you should always sanitize inputs, use prepared statements,
//  and implement proper security measures.

function forgot_password($email) {
  // 1. Check if the email exists in the database.
  $query = "SELECT id, password FROM users WHERE email = ?";
  $stmt = $conn->prepare($query); // Assuming $conn is your database connection object.
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email not found
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique, secure token.  Important for security!
  $token = bin2hex(random_bytes(32)); // More secure than simple rand()

  // 3.  Create a password reset link.
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token . "&user_id=" . $user['id'];  // Replace with your domain.
                                   //  This URL should be safe and not expose sensitive data.

  // 4.  Store the token and user ID in the database.
  $query = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sss", $user['id'], $token, date("Y-m-d H:i:s", time() + 3600)); // Expire after 1 hour.  Adjust as needed.
  $result = $stmt->execute();

  if (!$result) {
      // Handle errors - log them, display a message, etc.  Don't just silently fail.
      error_log("Error creating password reset entry: " . error_get_last()['message']);
      return false;
  }


  // 5.  Send an email to the user with the reset link.
  $to = $email;
  $subject = "Password Reset";
  $message = "Click on the link below to reset your password:
" . $reset_link;
  $headers = "From: your_email@example.com\r
";
  mail($to, $subject, $message, $headers);  // Use a robust mailer library instead of mail() for production.


  return true;
}


// Example usage (after a user submits the forgot password form)
// $email = $_POST['email'];  // Get email from the form.
// if (forgot_password($email)) {
//   echo "Password reset email has been sent to " . $email;
// } else {
//   echo "Failed to generate password reset link.";
// }


?>
