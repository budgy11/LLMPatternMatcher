
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="post">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    // User found, set session variables for authentication
    session_start();
    $_SESSION['username'] = $username;
    return true;
  }
}


// Example Usage:  Handle Login Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    echo "Login successful! You are now logged in.";
    // Redirect (example)
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();

  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}

?>
