
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="john.doe" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="login" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Invalid username. Please use a valid email or alphanumeric characters only.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the login
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if the query was successful and if a user was found
    if ($result->num_rows > 0) {
      // User found, set session variables and redirect
      session_start();
      $_SESSION["username"] = $username; // Store the username in the session
      // Optionally store other user data here, like roles/permissions
      // if you have a more complex system.

      // Redirect the user to the home page or protected area
      header("Location: home.php"); // Replace 'home.php' with your desired page
      exit();
    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>
