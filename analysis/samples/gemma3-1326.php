

<?php

// Assuming you have a database connection established (e.g., $db)
// and that you've defined necessary constants like 'DB_HOST', 'DB_USER', etc.

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 3. Hash the token (important for security)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4.  Store the token and timestamp in the database
  $query = "INSERT INTO forgot_password_tokens (email, token, hashed_token, created_at) 
            VALUES ('$email', '$token', '$hashed_token', NOW())";

  if (!mysqli_query($db, $query)) {  // Replace 'db' with your database connection
    return 'Error inserting token into database.';
  }

  // 5.  Send the password reset email (you'll need to implement this)
  $to = $email;
  $subject = 'Password Reset Request';
  $message = 'Please click on the following link to reset your password: ' . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '">' . 'Reset Password' . '</a>';
  $headers = 'From: your_email@example.com' . "\r
";

  if (mail($to, $message, $headers)) {
    return 'Password reset email sent to ' . $email;
  } else {
    return 'Failed to send password reset email.';
  }
}


// Example usage (within a form or other script)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_link = forgot_password($email);

  echo "<p>" . $reset_link . "</p>";
}

?>
