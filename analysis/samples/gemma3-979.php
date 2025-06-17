
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h2>User Login</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php
session_start(); // Start the session

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Login function
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security -  never directly use user input in SQL without sanitization!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    $login_result = login($username, $password);

    if ($login_result) {
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
