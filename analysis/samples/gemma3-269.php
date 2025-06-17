
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (very basic example, improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start(); // Start the session
      $user_row = $result->fetch_assoc();
      $_SESSION["username"] = $user_row["username"];
      $_SESSION["user_id"] = $user_row["id"]; // Assuming you have an id column in your users table
      // You might want to add more user data to the session if needed
      echo "Login successful!  Redirecting...";
      header("Location: user_dashboard.php"); // Redirect to a secure page
      exit(); // Important: Stop further script execution
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
