    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Replace with your registration page -->


</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Incorrect username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables
      session_start(); // Start the session (if not already started)
      $_SESSION["username"] = $username; // Store username in session
      header("Location: welcome.php"); // Redirect to a welcome page
      exit(); // Important: Stop further script execution
    }
  }
}

// Display login form if not posting data
?>
