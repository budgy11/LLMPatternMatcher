
<!-- HTML Form for the Reset Password Request -->
<form method="post" action="">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required><br><br>
  <button type="submit">Reset Password</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db)
// and that you have a table named 'users' with an 'email' column.

function forgot_password($email, $db) {
  // 1. Verify email exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Using bin2hex for better security

    // 3.  Hash the token (important for security)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);  // Use password_hash

    // 4.  Store the token in the database, associated with the user.
    $db->query("UPDATE users SET password_reset_token = '$hashed_token' WHERE id = $userId");

    // 5.  Email the user with a reset link
    $reset_link = "http://yourwebsite.com/reset_password?token=$token"; // Replace with your website URL

    // Email functionality - (This part needs to be implemented with your email sending method)
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click on the following link to reset your password: $reset_link";
    $headers = "From: yourwebsite@example.com";  // Customize your email sender
    mail($to, $message, $headers);

    return true; // Indicate successful password reset request
  } else {
    return false; // Email not found
  }
}


// --- Example usage (Illustrative - Adapt to your application logic) ---
// $email = "testuser@example.com";
// if (forgot_password($email, $db)) {
//   echo "Password reset email has been sent to $email.";
// } else {
//   echo "Email not found or an error occurred.";
// }

?>
