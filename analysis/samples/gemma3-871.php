    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (important for security - never trust user input!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
    $error_message = "Username must contain only letters, numbers, and underscores.";
  } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
    $error_message = "Password must contain only letters and numbers.";
  } else {
    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // Login successful - set a session variable
      session_start();
      $_SESSION["username"] = $username;
      $error_message = "Login successful!";
      // Redirect to a secure page (e.g., the homepage)
      header("Location: home.php"); // Replace 'home.php' with your secure page
      exit;
    }
  }
}
?>
