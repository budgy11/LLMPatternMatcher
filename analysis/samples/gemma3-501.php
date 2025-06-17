
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
session_start();

/**
 * Handles user login functionality.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to authenticate.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Database connection (Replace with your actual database connection details)
  $dbHost = 'localhost';
  $dbName = 'your_database_name';
  $dbUser = 'your_username';
  $dbPassword = 'your_password';

  // Credentials for database connection
  $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id']; // Assuming you have an ID column in your users table
    return true;
  } else {
    return false;
  }

  $conn->close();
}


/**
 * Handles user logout functionality.
 */
function logoutUser() {
  // Destroy session variables
  session_unset();
  session_destroy();
}

// Example Usage (Demonstration -  This part won't run on its own)
// This is just to illustrate how you'd use the functions.

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display content
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

// Logout button example (replace with your actual button)
if (isset($_GET['logout'])) {
  logoutUser();
  echo "<p>You have been logged out.</p>";
  header("Location: /index.php"); // Redirect back to the homepage
}

?>
