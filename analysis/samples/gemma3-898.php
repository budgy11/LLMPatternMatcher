
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query to authenticate
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // User found, set session variables for authentication
  $row = $result->fetch_assoc();
  $_SESSION['user_id'] = $row['id'];
  $_SESSION['username'] = $row['username'];
  // You might want to add more user data to the session as needed
  echo "Login successful! You are now logged in as " . $_SESSION['username'];
  // Redirect to a protected page or display a welcome message
  header("Location: /protected_page.php"); // Replace with your protected page
  exit();
} else {
  // User not found
  echo "Invalid username or password.";
}

$conn->close();

?>
