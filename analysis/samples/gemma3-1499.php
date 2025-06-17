

<?php

// Database connection details (replace with your actual details)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 2.  Securely Hash the New Password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Prepare and Execute the SQL Query
  $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    return "Password reset successful. Please check your email for further instructions.";
  } else {
    return "Error resetting password: " . $conn->error;
  }

  // 4. Close the connection
  $conn->close();
}

// Example Usage (Demonstration - Don't expose this directly in a public-facing application!)
//  This part demonstrates how to call the function.
//  In a real application, you'd get the email and new password from a form submission.

// Simulate getting data from a form (for demonstration only)
$email = 'test@example.com';  // Replace with a valid email address
$newPassword = 'newSecurePassword123';  // Replace with the desired new password

// Call the function
$result = forgotPassword($email, $newPassword);

// Display the result
echo $result;

?>
