    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Replace with your registration page -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    }
  }
} else {
  $error = ""; // Clear the error message if the form hasn't been submitted
}
?>
