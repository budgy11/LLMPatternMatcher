
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['username'];
    $_SESSION['id'] = $user['id']; // Assuming you have an 'id' column in your users table
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  if (loginUser($username, $password)) {
    // Redirect to a secure page after successful login
    header("Location: secure_page.php");
    exit();
  } else {
    // Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}
?>
