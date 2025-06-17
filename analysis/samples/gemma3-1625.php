

<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with an 'email' column.

// 1.  Check if the 'reset_password_request' parameter is present in the URL.
if (isset($_GET['reset_password_request'])) {
  // 2.  Get the user's email from the URL parameter.
  $email = $_GET['reset_password_request'];

  // 3.  Validate the email address (basic validation - consider more robust validation in production)
  if (empty($email)) {
    // Display an error message
    echo "<p style='color:red;'>Invalid Email Request.</p>";
    exit;
  }

  // 4.  Fetch the user from the database based on the email.
  $stmt = $db->prepare("SELECT id, password, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  // 5. Check if the user exists.
  if ($result->num_rows == 0) {
    // User not found
    echo "<p style='color:red;'>User not found with this email address.</p>";
    exit;
  }

  $user = $result->fetch_assoc();

  // 6.  Generate a unique, time-based token.  Use a cryptographically secure method for generating the token.
  $token = bin2hex(random_bytes(32)); // Generate a 32-byte random string
  // IMPORTANT:  Store this token in your database associated with the user's ID.
  // This ensures that the token is only valid for the user who requested it.

  // 7.  Prepare the SQL query to update the user's password.
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_expiry = ? WHERE id = ?");
  $stmt->bind_param("sss", $token, time() + 3600, $user['id']); // expiry after 1 hour
  $stmt->execute();

  // 8.  Send an email to the user with a link to reset their password.  This is the most important part.
  $to = $email;
  $subject = 'Password Reset Request';
  $message = "Please click the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset_password_request=' . urlencode($token) . '"' . ' target="_blank" >Reset Password</a>';
  $headers = "From: your_email@example.com" . "\r
"; // Replace with your email

  mail($to, $subject, $message, $headers);

  // 9.  Display a success message to the user.
  echo "<p style='color:green;'>Password reset link has been sent to your email address.</p>";
  echo "<p>You should receive an email with instructions on how to reset your password.</p>";

} else {
  // If the 'reset_password_request' parameter is not present,
  // likely the user clicked a link that's expired.
  echo "<p style='color:red;'>Invalid or expired password reset link.</p>";
  exit;
}

?>
