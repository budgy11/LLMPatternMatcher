
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form action="" method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to check for the username and password
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 0) {
        $error_message = "Invalid username or password.";
      }

      if ($result->num_rows > 0) {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $error_message = "Login successful!";

        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php");  // Replace with your dashboard page
        exit(); // Stop further script execution
      }
    }
  }
}
?>
