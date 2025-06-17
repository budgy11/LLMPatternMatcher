

<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For this example, we'll assume a simple $db connection is already set up

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); //  More secure than older methods
  // Consider storing the token in a database table (e.g., 'reset_tokens')

  // 3.  Set up the token expiry (optional, but recommended)
  //   -  This prevents tokens from being used indefinitely.
  $expiry = time() + 3600; // Token expires in 1 hour (3600 seconds)


  // 4.  Simulate database insertion (replace with your actual database logic)
  //   This part simulates inserting the token into a database table.
  //   In a real application, this would use a database query to insert
  //   the email and token into the 'reset_tokens' table.

  $reset_token_data = [
    'email' => $email,
    'token' => $token,
    'expiry' => $expiry,
    'created_at' => time() // Record the creation timestamp
  ];

  //  Example:  Storing in an array for demonstration.
  //  In a real application, this would be a database insertion.
  //  $db->insert('reset_tokens', $reset_token_data);


  // 5.  Send the reset link (e.g., via email)
  //   -  Build the reset link URL
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token . "&expiry=" . $expiry;

  //   -  Send the email. You can use a library like PHPMailer.
  //   -  Example using a simple echo to demonstrate the link.
  //   echo "<p>Please click the following link to reset your password:</p><a href='" . $reset_link . "' >Reset Password</a>";


  // 6.  Return a success message (or any relevant information)
  return "A password reset link has been sent to your email address.  Please check your inbox.";
}


// Example usage:
$email_to_reset = "testuser@example.com"; // Replace with the user's email
$reset_message = forgot_password($email_to_reset);
echo $reset_message;
?>
