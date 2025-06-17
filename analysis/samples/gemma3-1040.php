    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Confirm Password: <input type="password" name="confirm_password" required><br><br>
    <input type="submit" value="Register">
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (
    $username == "admin" && $password == "password" // Hardcoded credentials - **NEVER DO THIS IN PRODUCTION!**
  ) {
    // Successful login
    session_start();
    $_SESSION["username"] = $username;
    $error = ""; // Clear any previous errors
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}

?>
