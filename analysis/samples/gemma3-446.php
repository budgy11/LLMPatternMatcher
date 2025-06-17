
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

// Form data from the login form
$login_username = $_POST['username'];
$login_password = $_POST['password'];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query
$sql = "SELECT username, password FROM users"; // Replace 'users' with your table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Fetch the data
  $row = $result->fetch_assoc();

  // Verify credentials
  if (password_verify($login_password, $row['password'])) {
    // Login successful
    // Set session variables to track the user
    session_start();
    $_SESSION['username'] = $row['username'];
    $_SESSION['user_id'] = $row['id']; // Assuming you have an id column in your table

    // Redirect to a welcome page or desired location
    header("Location: welcome.php"); // Replace 'welcome.php' with your welcome page
    exit();  // Important:  Prevent further script execution
  } else {
    // Login failed - password incorrect
    echo "Incorrect password.";
  }
} else {
  // No users found
  echo "No users found.";
}

// Close the connection
$conn->close();

?>
