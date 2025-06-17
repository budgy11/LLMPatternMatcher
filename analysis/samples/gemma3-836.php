
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the login form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters

  // Execute the statement
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if the query returned any rows
  if ($result->num_rows > 0) {
    // User found, set a session variable
    $_SESSION["username"] = $username;
    echo "Login successful!  You are now logged in as " . $username;
    header("Location: /home.php");
    exit();

  } else {
    echo "Incorrect username or password.";
  }

  $stmt->close();
  $conn->close();
}
?>
