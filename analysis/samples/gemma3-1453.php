

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Generate a random token for password reset
function generate_reset_token() {
  return bin2hex(random_bytes(32));
}

// Function to reset password
function reset_password($email, $token, $new_password) {
  // 1. Validate the token
  $query = "SELECT id FROM users WHERE email = ? AND reset_token = ?";
  $stmt = $GLOBALS['conn']->prepare($query);
  $stmt->bind_param("ss", $email, $token);
  $result = $stmt->execute();

  if ($result) {
    $user = $stmt->fetch_assoc();
    $id = $user['id'];

    // 2. Update the password in the database
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

    $query = "UPDATE users SET password = ?, reset_token = ? WHERE id = ?";
    $stmt = $GLOBALS['conn']->prepare($query);
    $stmt->bind_param("sisi", $password_hash, $token, $id);

    if ($stmt->execute()) {
      return true; // Password reset successful
    } else {
      return false; // Password reset failed
    }
  } else {
    return false; // Token invalid or user not found
  }
}


// Example Usage:

// 1. Generate the token
$reset_token = generate_reset_token();
echo "Reset Token: " . $reset_token . "<br>";

// 2.  Simulate a request from the user's browser (for demonstration)
//  In a real application, you'd receive the email, token, and new password from a form.
$user_email = "test@example.com"; // Replace with the user's email
$new_password = "newPassword123";  // Replace with the new password

// 3.  Call the reset_password function
if (reset_password($user_email, $reset_token, $new_password)) {
  echo "Password reset successful! Please check your email.";
} else {
  echo "Password reset failed.  Check your email address and token.";
}


// ---  Important Considerations & Enhancements ---
// 1.  Database Connection:
//    - Replace  $host, $username, $password, and $database with your actual credentials.
//    - Use a prepared statement to prevent SQL injection vulnerabilities.

// 2.  Token Expiration:
//    - Add an expiration time to the token (e.g., 30 minutes). This prevents attackers from using old tokens.  You can use `DateTime` and `strtotime()` for this.

// 3.  Email Sending:
//    - Use a reliable email sending service (e.g., SendGrid, Mailgun, SMTP) to send the password reset link to the user.  Don't try to implement your own email sending as it's prone to issues and can be blacklisted.

// 4.  Security:
//    - Never store passwords in plain text. Always use a strong hashing algorithm (like PASSWORD_DEFAULT) with a salt.
//    - Validate all user input to prevent XSS and other vulnerabilities.
//    - Rate limiting to prevent brute-force attacks.

// 5.  Error Handling:
//    - Implement more robust error handling and logging.

// 6.  User Interface:
//    - Design a user-friendly interface for the password reset process.  Include clear instructions and feedback.
?>
