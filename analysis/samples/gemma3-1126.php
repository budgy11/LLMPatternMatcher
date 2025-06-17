
<!-- HTML Form (for simplicity, placed here) -->
<form method="post" action="">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username">
  <br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password">
  <br>
  <button type="submit" name="login">Login</button>
</form>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - discussed below)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to check username and password
      $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found, check password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          // Password matches, login successful
          session_start();
          // Store user information in session
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $username;

          // Redirect to a protected page (e.g., dashboard)
          header("Location: dashboard.php"); // Replace with your protected page
          exit();
        } else {
          // Password doesn't match
          $error_message = "Incorrect password.";
        }
      } else {
        // User not found
        $error_message = "User not found.";
      }
    }
  }
}
?>
