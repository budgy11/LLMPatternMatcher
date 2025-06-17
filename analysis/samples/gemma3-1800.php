

<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle password reset
function forgot_password($email) {
  // 1. Check if the email exists in the user table
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email not found
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator
  // 3. Store the token in the database for the user.
  $stmt = $db->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
  $stmt->bind_param("ss", $token, $email);
  $result = $stmt->execute();

  if ($result === false) {
    // Handle database error
    error_log("Error updating reset token: " . error_get_last()['message']); // Log the error
    return false;
  }

  // 4. Send the reset password link via email
  $subject = "Password Reset Link";
  $message = "Click the link below to reset your password:
" .
             "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>Reset Password</a>";
  $headers = "From: your_email@example.com\r
";
  mail($email, $message, $headers);

  // 5.  Return true to indicate success
  return true;
}


// Example Usage (In a form submission or similar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (!empty($email)) {
    if (forgot_password($email)) {
      echo "<p>Password reset link sent to your email address.</p>";
    } else {
      echo "<p>Failed to send password reset link. Please try again.</p>";
    }
  } else {
    echo "<p>Please enter your email address.</p>";
  }
}
?>
