    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example - improve for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (
    $username == "admin" && $password == "password" // Replace with your actual admin credentials
  ) {
    // Authentication successful
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    header("Location: /welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>
