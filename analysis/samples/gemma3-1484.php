

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Database Connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    // Handle connection error
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Check if email exists
  $query = "SELECT id FROM users WHERE email = '{$email}'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Email exists, proceed with password reset
    $userId = $result->fetch_assoc()['id'];

    // 3. Generate a unique token (for security)
    $token = bin2hex(random_bytes(32)); // Generate a 32-byte random binary string and convert it to a hex string.

    // 4. Update the password and add the token to the database
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);  // Hash the new password

    $query = "UPDATE users SET password = '{$hashedNewPassword}', reset_token = '{$token}' WHERE id = {$userId}";

    if ($conn->query($query) === TRUE) {
      // Password reset successful
      // Send an email with the token and instructions
      // (Implement email sending logic here - see example below)
      echo "Password reset successful. Please check your email for instructions.";
    } else {
      // Handle error
      echo "Error resetting password. " . $conn->error;
    }
  } else {
    // Email doesn't exist
    echo "Email not found.";
  }

  $conn->close();
}


// Example usage (from a form submission - DO NOT put this directly in your HTML)
// Assume the user entered the email and the new password from a form.

//Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["new_password"];

  forgotPassword($email, $newPassword);
}
?>
