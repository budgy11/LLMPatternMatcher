

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to reset the password
function forgotPassword($email, $newPassword) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the new password
  $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Update the password in the database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    return true;  // Password reset successful
  } else {
    return false;  // Password reset failed
  }

  // 4. Close the connection
  $conn->close();
}


// Example Usage (for demonstration purposes - integrate with your form)
//  This is for testing, usually you'd handle the form submission separately.

// Example email: Replace with the actual email address
$email_to = 'test@example.com';
$new_password = 'newSecretPassword123'; // Replace with a secure password

if (forgotPassword($email_to, $new_password)) {
  echo "Password reset successful! Please check your email.";
} else {
  echo "Password reset failed.  Please try again.";
}


//  Important Considerations and Best Practices:

// 1. Security:
//    * Never store passwords in plain text. Always use password hashing.  `password_hash()` with `PASSWORD_DEFAULT` is the recommended approach.
//    * Use prepared statements to prevent SQL injection vulnerabilities.  (While this example doesn't use prepared statements, it's *crucial* for any real-world implementation)
//    * Implement rate limiting to prevent brute-force attacks.  Don't allow users to request password resets too frequently.
//    * Consider using a token-based approach to password resets, where a unique token is generated and sent to the user's email. This token is then used to verify the user's request.

// 2. Error Handling:
//    * Implement comprehensive error handling.  Log errors to a file or database for debugging.  Don't just display generic "failed" messages.
//    * Check if the email address exists in the database before attempting to update the password.

// 3. User Experience:
//    * Provide clear and helpful instructions to the user.
//    * Send a confirmation email to the user after the password reset is successful.
//    *  Consider adding a "forgot password" link on the login page.

// 4.  Email Sending (not included in this example, but essential)
//     * You'll need to use a library or function to send emails.  Popular options include:
//       * PHPMailer
//       * SwiftMailer

// 5.  Password Complexity Requirements:
//    * Enforce password complexity rules (minimum length, required characters, etc.)  You can do this on the client-side (using JavaScript) or server-side (in your PHP code) or ideally both.
?>
