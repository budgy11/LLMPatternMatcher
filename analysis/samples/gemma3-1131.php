    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to your registration page -->

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example - improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and Password are required.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // Use prepared statements for security!
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;
      // Redirect to a secure page or display a welcome message
      header("Location: welcome.php"); // Assuming you have a welcome.php page
      exit();
    }
  }
}
?>
