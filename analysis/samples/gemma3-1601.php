

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with columns 'id', 'email', and 'password'

// Function to handle password reset requests
function forgotPassword($email, $db) {
  // 1. Validate Email (Crucial for security!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the database
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    // Email exists, generate a unique token and store it in the database
    $token = bin2hex(random_bytes(32)); // Generate a secure random token
    $db->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    // 3. Send an email with a reset link
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . base_url() . "/reset-password?token=$token";  //  base_url() needs to be defined in your config
    $headers = "From: admin@example.com" . "\r
";

    mail($to, $message, $headers);
    return "Password reset email sent to $email.";

  } else {
    return "Email not found in our database.";
  }
}

// Example usage (assuming you have a base_url function defined)
//  This is just for demonstration.  Integrate this into your application logic.
//  Don't directly execute this example code in a deployed environment!
//
// $email = $_POST['email']; // Get email from form submission
// $reset_response = forgotPassword($email, $db);
// echo $reset_response;

?>
