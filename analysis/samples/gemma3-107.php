    <br />
    Password:
    <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br />
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle login
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user based on username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage (Handles login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // Redirect to a welcome page or wherever you want to go after login
    header("Location: welcome.php"); // Replace welcome.php with your welcome page
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}

?>
