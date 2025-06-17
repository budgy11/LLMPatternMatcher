
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - prevent SQL injection)
  $username = trim($username);  // Remove leading/trailing whitespace
  $password = trim($password);

  // Sanitize input (important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start(); // Start the session (if not already started)
    $_SESSION["username"] = $username;
    echo "Login successful!";
    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php");  // Replace with your welcome page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
else {
  // If the form wasn't submitted, display the login form
  // You can either include this file or display a simple HTML form
  // For example:
  echo "<form method='post' action='login.php'>";
  echo "Username: <input type='text' name='username'>";
  echo "<br>";
  echo "Password: <input type='password' name='password'>";
  echo "<br>";
  echo "<input type='submit' value='Login'>";
  echo "</form>";
}

?>
