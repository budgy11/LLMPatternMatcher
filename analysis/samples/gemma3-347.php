
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    <br>
    Password: <input type="password" name="password">
    <br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $result->fetch_assoc(); // Fetch the row data
        $error = "";  // Clear any previous errors
        // Redirect to a secure page or display a welcome message
        header("Location: welcome.php"); // Redirect to a welcome page
        exit(); // Stop further execution
      } else {
        $error = "Incorrect username or password.";
      }
    }
  }
}

// Display the login form if no form was submitted or if there's an error
?>
