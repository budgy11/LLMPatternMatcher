

<?php

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    // Handle the connection error.  This is important for debugging.
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the new password for security
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. SQL Query
  $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";

  // 4. Execute the Query
  if ($conn->query($sql) === TRUE) {
    // Password updated successfully
    echo "Password reset successfully.  Please check your email for details.";
  } else {
    // Handle query error
    echo "Error updating password: " . $conn->error;
  }

  // 5. Close the connection
  $conn->close();
}


// Example Usage (this part is just for demonstration.  It will NOT be part of the actual password reset process.)
// It's used to show how the function would be called.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["newPassword"];

  // *** IMPORTANT:  In a real application, you would NOT directly use user input
  //  like this for password resetting.
  //  You'd implement proper input validation, sanitization, and error handling.
  //  This example is for demonstration purposes only.

  forgotPassword($email, $newPassword);
}
?>
