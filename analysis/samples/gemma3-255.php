
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
session_start(); // Start the session

/**
 * Handles user login functionality.
 *
 * @param string $username The username to login.
 * @param string $password The password for the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Validate inputs (important for security)
  if (empty($username) || empty($password)) {
    return false;
  }

  // Database connection (replace with your actual database details)
  $db_host = 'localhost';
  $db_name = 'your_database_name';
  $db_user = 'your_database_user';
  $db_password = 'your_database_password';

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password (hash comparison - IMPORTANT!)
    if (password_verify($password, $row['password'])) {
      // Login successful
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;

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


// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a welcome page or other appropriate location
    header("Location: welcome.php"); // Replace 'welcome.php' with your desired page
    exit();
  } else {
    // Login failed, display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
