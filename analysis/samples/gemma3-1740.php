

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgotPassword($email, $new_password, $db) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return false;
  }

  // Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // SQL query to update the password for the given email
  $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

  // Execute the query
  if ($conn->query($query) === TRUE) {
    return true;
  } else {
    return false;
  }
}


// Example Usage (Demonstration -  Don't use this directly in a web app without proper form validation and security)
// In a real application, this would be triggered by a user submitting a "forgot password" form.

// Simulate a password reset request.  This is just for testing.
// Replace 'user@example.com' with the actual email address
$email = "user@example.com";
$new_password = "new_secure_password";

// Call the forgotPassword function
if (forgotPassword($email, $new_password, $conn)) {
  echo "Password reset email sent successfully (simulated).";
} else {
  echo "Password reset failed. Check database connection and email address.";
}

// Close the connection (important!)
$conn->close();


//  ---  Important Considerations and Security Best Practices  ---

// 1.  Input Validation and Sanitization:
//     * **Form Validation:**  In a real application, *always* validate the email address format and the new password before sending it to the database. Use HTML5 validation on the front-end and server-side validation.
//     * **Sanitize Input:**  Sanitize the input to prevent SQL injection vulnerabilities.  Use prepared statements (recommended) or proper escaping functions.  The example provided is vulnerable if not using prepared statements.
//     * **Password Complexity:** Enforce password complexity requirements (minimum length, special characters, etc.) to improve security.

// 2.  Email Sending:
//     * **Use a reliable email sending service:** Do *not* directly embed your email credentials into the PHP code.  Use a reputable email sending service (e.g., SendGrid, Mailgun, AWS SES) to handle email delivery.
//     * **Token-Based Reset:** Implement a token-based password reset system, where a unique token is generated and sent to the user's email address.  This token should expire after a certain period. This prevents unauthorized password resets.  The user clicks the link in the email to set a new password.

// 3.  Security:
//     * **HTTPS:** Always use HTTPS to encrypt the communication between the user's browser and your server.
//     * **Rate Limiting:** Implement rate limiting to prevent brute-force attacks on the password reset endpoint.
//     * **Logging:** Log all password reset requests for auditing and security monitoring.

// 4.  Prepared Statements (Highly Recommended):
//    Prepared statements are the *best* way to prevent SQL injection vulnerabilities. They ensure that user input is treated as data, not as part of the SQL query.

//  Example using prepared statements (using mysqli):

/*
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $new_password, $email);
$stmt->execute();
$stmt->close();
*/
?>
