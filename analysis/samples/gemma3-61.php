
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if the user exists
    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables to keep user logged in
      $_SESSION["username"] = $username;
      // Optionally, store user ID or other relevant data in the session
      // $_SESSION["user_id"] = $user_id;

      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php"); // Replace 'dashboard.php' with your desired redirect
      exit();
    }
  }
}

// Check if the session is already set, redirecting if it is
if (isset($_SESSION["username"])) {
  header("Location: dashboard.php");
  exit();
}

?>
