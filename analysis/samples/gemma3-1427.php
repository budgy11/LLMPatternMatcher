

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 3. Hash the new password (important for security!)
  $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the password in the database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    return "Password reset successful. Check your email for verification instructions (if applicable).";
  } else {
    return "Error resetting password. " . $conn->error;
  }

  // 5. Close the connection
  $conn->close();
}


//  Example usage (Illustrative - you'd get this from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["new_password"];

  $result = forgotPassword($email, $newPassword);
  echo $result;
} else {
  // If this is not a post request, display a form
  echo '<form method="post" action="">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required><br><br>
          <label for="new_password">New Password:</label>
          <input type="password" id="new_password" name="new_password" required><br><br>
          <input type="submit" value="Reset Password">
        </form>';
}

?>
