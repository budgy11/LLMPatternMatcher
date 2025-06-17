
<form action="" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email address">
    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// This is a simplified example and should be adapted for your specific setup.

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // 's' indicates a string parameter
  $result = $stmt->execute();

  if ($result === false) {
    // Handle database error
    error_log("Error executing query: " . $db->error);
    return "An error occurred while processing your request.";
  }

  $row = $stmt->get_result()->fetch_assoc();

  if ($row === null || empty($row)) {
    return "Email address not found.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random string

  // 3.  Create a password reset token record in the database
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $row['id'], $token, time() + 60 * 60 * 24); // Expires in 24 hours
  $result = $stmt->execute();

  if ($result === false) {
    // Handle database error
    error_log("Error inserting into password_resets: " . $db->error);
    return "An error occurred while processing your request.";
  }

  // 4.  Send an email to the user with the reset link
  $subject = "Password Reset Link";
  $message = "Click on the following link to reset your password:
";
  $message .= "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>";
  $message .= "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>"; // Use $_SERVER['PHP_SELF'] to redirect correctly
  $message .= "Reset Password</a>";
  $message .= "<br><br>This link will expire in 24 hours.";
  $headers = "From: Your Website <admin@yourwebsite.com>\r
";

  if (mail($email, $subject, $message, $headers)) {
    return "Password reset email sent.  Please check your inbox.";
  } else {
    return "Failed to send password reset email.";
  }
}

// Example usage (assuming you have a form to submit the email)
//  Suppose you have an email form with a field named "email".
//  You would call the function like this:

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $email = $_POST["email"];
//   $reset_message = forgotPassword($email);
//   echo $reset_message;
// }
?>
