
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="john.doe" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Basic validation - enhance as needed)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // Query the database to check for the user
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // IMPORTANT:  Sanitize input properly in a production environment!
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // User found, set session variables and redirect
      session_start();
      $_SESSION["username"] = $username;
      // Add any other data you want to store from the user (e.g., role, etc.)
      // $_SESSION["role"] = "admin"; // Example
      // Redirect to a secure page
      header("Location: secure_page.php"); // Replace with the name of your secure page
      exit(); // Important to stop further execution
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>
