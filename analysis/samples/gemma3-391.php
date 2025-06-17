    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - Add more robust validation)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  }
  // In a real application, you would compare the input against the
  // database, properly hashed for security.  This is a simplified example.

  // Simulate a login check (replace with actual database query)
  if ($username == "admin" && $password == "password") {
    // Login successful
    session_start();
    $_SESSION["username"] = $username;
    header("Location: /welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    $error_message = "Invalid username or password.";
  }
}
?>
