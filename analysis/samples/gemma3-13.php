    Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="' . $username . '"'; ?> />
    <br/>
    Password: <input type="password" name="password" />
    <br/>
    <button type="submit">Login</button>
  </form>

  <br/>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // User found, set session variables
  session_start();
  $_SESSION['username'] = $username;
  $_SESSION['user_id'] = $result->fetch_assoc()['user_id']; // Assuming you have a user_id column

  // Redirect to a secure page (e.g., dashboard)
  header("Location: dashboard.php"); // Replace dashboard.php with your secure page URL
  exit();
} else {
  // User not found
  echo "Invalid username or password.";
}

$conn->close();

?>
