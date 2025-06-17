

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column.

function forgotPassword($email) {
  // 1. Validate Input
  if (empty($email)) {
    return "Please enter your email address.";
  }

  // 2. Check if the email exists in the database.  Crucially, sanitize the input!
  $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR); // Important: Specify data type
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "Email not found.";
  }

  // 3. Generate a Unique Token
  $token = bin2hex(random_bytes(32));  // Generates a random 32-byte (256-bit) hex string.
  //  This is much more secure than generating random strings.

  // 4.  Prepare Password Reset Link -  Store the token and user ID in the database.
  //     This is where you'd typically insert a new row into the users table
  //     with a 'reset_token' and 'reset_expiry' column.  For this example,
  //     we'll just print the link (in a real application, you'd use a proper
  //     link generation library).
  $db->prepare("INSERT INTO password_resets (user_id, reset_token, expiry) VALUES (:user_id, :reset_token, :expiry)");
  $db->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
  $db->bindParam(':reset_token', $token, PDO::PARAM_STR);
  $db->bindParam(':expiry', time() + 3600, PDO::PARAM_INT);  // Token expires in 1 hour.
  $db->execute();


  // 5. Build the Password Reset Link
  $resetLink = "http://yourdomain.com/reset_password?token=" . $token;  // Replace yourdomain.com

  // 6. Send the Email (You'll need to implement this)
  $to = $email;
  $subject = "Password Reset";
  $message = "Click the link below to reset your password:
" . $resetLink;
  $headers = "From: your_email@example.com
";
  mail($to, $message, $headers); // This is a simple mail function.  Use a proper library.

  // 7.  Return a confirmation message.
  return "Password reset link has been sent to your email address.  Please check your inbox.";
}



// Example Usage (for testing - remove or comment out in production)
// $email = "testuser@example.com"; // Replace with a valid email
// $result = forgotPassword($email);
// echo $result;
?>
