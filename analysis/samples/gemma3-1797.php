

<?php

// Database connection (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2. Connect to Database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    // Handle connection error (Log it, display an error message, etc.)
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  // 3. Hash the new password (VERY IMPORTANT for security)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. SQL Query - Update the password
  $sql = "UPDATE users SET password = '$hashedNewPassword' WHERE email = '$email'"; 

  if ($conn->query($sql) === TRUE) {
    return true; // Password updated successfully
  } else {
    // Handle query error (Log it, display an error message, etc.)
    error_log("Query failed: " . $conn->error);
    return false;
  }

  // 5. Close the connection
  $conn->close();
}


// Example Usage (This is just for testing - NEVER expose this in a public-facing application!)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["newPassword"];

  if (empty($email) || empty($newPassword)) {
    echo "<p>Email and new password are required.</p>";
  } else {
    // Call the function to reset the password
    $resetResult = forgotPassword($email, $newPassword);

    if ($resetResult) {
      echo "<p>Password reset successful. Please check your email.</p>";
    } else {
      echo "<p>Password reset failed. Please try again.</p>";
    }
  }
}
?>
