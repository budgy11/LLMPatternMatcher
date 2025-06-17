
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// For demonstration purposes, we'll use a hardcoded database
$db_host = 'localhost';
$db_name = 'my_database';
$db_user = 'my_user';
$db_password = 'my_password';

// Function to reset the password
function forgotPassword($email, $newPassword) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 3. Hash the new password (IMPORTANT for security)
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the user's password in the database
  $query = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'"; // Replace 'users' with your table name
  if ($conn->query($query) === TRUE) {
    return true; // Password reset successful
  } else {
    error_log("Query failed: " . $conn->error); // Log the error for debugging
    return false; // Password reset failed
  }

  // 5. Close the connection
  $conn->close();
}


// Example Usage (Simulated Form Handling - REPLACE with your actual form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["new_password"];

  if (empty($email) || empty($new_password)) {
    echo "<p style='color: red;'>Email and new password are required.</p>";
  } else {
    // Call the forgotPassword function
    if (forgotPassword($email, $newPassword)) {
      echo "<p style='color: green;'>Password reset successful!  Check your email.</p>";
      // You might want to send an email notification here
    } else {
      echo "<p style='color: red;'>Password reset failed. Please try again.</p>";
    }
  }
}

?>
