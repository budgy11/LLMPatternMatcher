        Username: <input type="email" name="username" required>
        <br><br>
        Password: <input type="password" name="password" required>
        <br><br>
        <button type="submit">Login</button>
    </form>

    <br><br>
    <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format for username.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // If the query is successful, set a session variable
      session_start();
      $_SESSION["username"] = $username;
      $error = "Login successful!";
      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php");  // Replace dashboard.php with your secure page
      exit();
    }
  }
}
?>
