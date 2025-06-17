

<?php

// Assuming you have a database connection established
// (e.g., $db connection object)
// and that you've defined a 'users' table with an 'email' column.

// Function to handle password reset
function forgotPassword($email, $resetToken, $resetHash, $db) {
  // 1. Check if the token exists in the database
  $query = "SELECT id FROM reset_tokens WHERE email = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $email, $resetToken);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Token not found
    return false;
  }

  // 2. If the token exists, update the 'used' column to true
  $query = "UPDATE reset_tokens SET used = 1 WHERE email = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $email, $resetToken);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Error updating token -  rare, but handle it.
    error_log("Error updating reset token: " . $db->error); // Log for debugging
    return false;
  }

  // 3. Send an email with a reset link (implement this part)
  //   -  Generate a temporary password and email it to the user
  //   -  Include a link to the password reset form with the token
  //   -  Set an expiration time for the token
  
  // Example:  (Replace with your email sending logic)
  $subject = "Password Reset Link";
  $to = $email;
  $body = "Click on this link to reset your password: " . $_SERVER['REQUEST_URI'] . "?token=" . $resetToken;

  //  Use a real email sending function here (e.g., sendmail, PHPMailer)
  //  Example using PHP's built-in mail function (simplest, but often unreliable):
  mail($to, $subject, $body);


  return true; // Token updated successfully
}


// Example Usage (for testing - this doesn't actually send an email)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetToken = $_POST["reset_token"];
  $resetHash = $_POST["reset_hash"];  // This would be a hash of a temp password.  Don't store plain text passwords!

  //  Simulate a database connection (replace with your actual connection)
  $db = new mysqli("localhost", "username", "password", "database_name");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  if (forgotPassword($email, $resetToken, $resetHash, $db)) {
    echo "Password reset request sent.  Check your email.";
  } else {
    echo "Password reset request failed.  Possibly an invalid token.";
  }
}
?>
