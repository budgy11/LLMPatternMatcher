    Username: <input type="text" name="username" />
    <br />
    Password: <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br />
  <a href="register.php">Don't have an account? Register here.</a>


</body>
</html>


<?php

// Database credentials - **IMPORTANT:  NEVER HARDCODE CREDENTIALS IN PRODUCTION!**
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic - improve for production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // SQL query using prepared statements
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($host, $sql);

    if ($stmt) {
      // Bind parameters
      mysqli_stmt_bind_param($stmt, "s", $username, $password); // "s" indicates a string

      // Execute the query
      mysqli_stmt_execute($stmt);

      // Get the result
      $result = mysqli_stmt_get_result($stmt);

      // Check for results
      if (mysqli_num_rows($result) > 0) {
        // User exists - set session variables
        $_SESSION["username"] = $username;

        // Redirect to a secure page
        header("Location: dashboard.php");
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }

      // Close the statement
      mysqli_stmt_close($stmt);
    } else {
      $error_message = "Database error preparing statement.";
    }
  }
}
?>
