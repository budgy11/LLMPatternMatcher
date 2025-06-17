

<?php

// Assuming you have a database connection established (e.g., $db)
// This is just a basic example and should be adapted to your specific setup.

// Function to handle password reset
function forgotPassword($email)
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Generate a Unique Token (important for security)
  $token = bin2hex(random_bytes(32));

  // 3.  Store Token in Database (or suitable storage)
  //  -  This is where you'd interact with your database to store the token
  //     associated with the user's email.
  //  -  Replace this with your actual database query.
  $query = "INSERT INTO forgot_passwords (email, token, expiry) VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)"; // Example query
  // Execute the query (e.g., $db->query($query));

  // 4. Create the Reset Link
  $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

  // 5. Send the Reset Email
  $to = $email;
  $subject = 'Password Reset Request';
  $message = "To reset your password, please click on the following link:
$resetLink

This link will expire in 24 hours.";
  $headers = 'From: your_email@example.com' . "\r
";

  mail($to, $message, $headers);

  return 'Password reset link sent to your email.';
}


// Example Usage (within a form submission or similar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"]; //  Get email from the form

  if ($email) {
    $result = forgotPassword($email);
    echo "<p>" . $result . "</p>";
  } else {
    echo "<p>Please enter your email address.</p>";
  }
}

?>
