    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// ... (Database credentials - same as before) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input
  if (empty($username) || empty($password)) {
    $error_message = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare the SQL query (using prepared statement)
      $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
      $stmt = $conn->prepare($sql);

      // Bind parameters
      $stmt->bind_param("ss", $username, $password); // "ss" means two strings

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 0) {
        $error_message = "Invalid username or password.";
      } else {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $error_message = "Login successful!";

        // Redirect
        header("Location: dashboard.php");
        exit();
      }

      // Close the statement
      $stmt->close();
    }
  }
}
?>
