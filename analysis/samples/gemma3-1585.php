
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and that you have a 'users' table with a 'email' and 'password' column.

function forgot_password($email, $db) {
  // 1. Verify email exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    // Email exists, generate a reset token
    $reset_token = bin2hex(random_bytes(32)); // Generate a random token
    $db->query("UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'");

    // 2. Send an email with the reset link
    $subject = "Password Reset Link";
    $message = "Click on the following link to reset your password: " .  $_SERVER['PHP_SELF'] . "?reset_token=$reset_token";
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email address
    mail($email, $subject, $message, $headers);

    return $reset_token; // Return the token for later use
  } else {
    return false; // Email not found
  }
}

// Example usage (assuming you have a form to submit the email)
//  (This is just a placeholder - you'd integrate this into your form handling)

//  Example:  If the user submits the email to reset_form.php
//  $email = $_POST['email'];
//  $token = forgot_password($email, $db);
//
//  if ($token) {
//    echo "Reset link sent to $email.  Please check your email.";
//  } else {
//    echo "Email not found or an error occurred.";
//  }

//  Example HTML form for submitting the email:
/*
<form method="post" action="reset_form.php">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Reset Password</button>
</form>
*/
?>
