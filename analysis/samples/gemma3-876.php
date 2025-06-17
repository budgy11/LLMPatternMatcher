    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password must be filled.";
  } elseif (
    $username == "admin" && $password == "password" // Example: Hardcoded credentials - **DO NOT USE THIS IN PRODUCTION!**
  ) {
    // Successful login
    session_start();
    $_SESSION["username"] = $username;
    header("Location: welcome.php"); // Redirect to a welcome page
    exit(); // Stop further execution
  } else {
    // Incorrect credentials
    $error_message = "Invalid username or password.";
  }
}

?>
