

<?php

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

// Function to handle password reset
function forgotPassword($email, $db) {
  // 1. Check if the email exists in the database
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    // 2. Generate a unique, temporary password reset token
    $token = bin2hex(random_bytes(32)); // Generate a secure random token

    // 3.  Store the token in the database, associated with the user's email
    $db->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    // 4.  Send an email with a link to reset the password.
    //     (Replace with your email sending logic)
    $subject = "Password Reset Request";
    $to = $email;
    $from = "your_email@example.com"; // Change this to your email address
    $message = "Click on the following link to reset your password: " . base64_decode("YOUR_BASE64_ENCODED_LINK") . " (This link expires in 1 hour.)"; // Replace with your actual link
    $headers = "From: " . $from . "\r
";
    $headers .= "Reply-To: " . $from . "\r
";
    mail($to, $message, $headers);

    return true; // Indicate success
  } else {
    return false; // Indicate email not found
  }
}


// --- Example Usage (Demonstration) ---

//  Create a dummy database connection (replace with your actual connection)
$db = new mysqli("localhost", "your_username", "your_password", "your_database");
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Example: Request a password reset for 'test@example.com'
$email = "test@example.com";
if (forgotPassword($email, $db)) {
  echo "Password reset email sent to $email";
} else {
  echo "Email not found or password reset failed.";
}

$db->close(); // Close the database connection when done

?>
