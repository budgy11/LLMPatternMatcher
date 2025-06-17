    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user based on username
    $sql = "SELECT id, username, password FROM users WHERE username = '" . $username . "'";  // Escape username for SQL injection. Use prepared statements for better security.

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // User found, verify password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Login successful
        session_start();
        // Store user ID in session for later access
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        echo "Login successful!  Redirecting...";
        header("Location: welcome.php"); // Redirect to a welcome page or your application's homepage
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>
