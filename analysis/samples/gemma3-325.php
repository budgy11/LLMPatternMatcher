
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

<br>
<a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start(); // Start the session

/**
 * User Login Function
 *
 * This function handles user login.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Database connection details (Replace with your actual credentials)
  $dbHost = "localhost";
  $dbUsername = "your_db_username";
  $dbPassword = "your_db_password";
  $dbName = "your_db_name";

  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Login successful
      $_SESSION['username'] = $username; // Store username in session
      $_SESSION['id'] = $row['id']; // Store user ID in session (optional, but recommended)
      return true;
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

/**
 *  Helper function to display a login form (for demonstration)
 */
function displayLoginForm() {
    ?>
