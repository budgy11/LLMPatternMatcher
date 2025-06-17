
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
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

  // SQL query to select user from the 'users' table
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";  //Sanitize input, use prepared statements for better security in real applications

  // Execute the query
  $result = $conn->query($sql);

  // Check if a user with the given username exists
  if ($result->num_rows > 0) {
    // Get the user data
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['password'])) { // Use password_verify
      // Login successful
      // Set session variables
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (login form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a welcome page or perform other actions
    header("Location: welcome.php"); // Assuming a welcome.php page exists
    exit();
  } else {
    // Login failed
    $error = "Invalid username or password.";
  }
}

?>
