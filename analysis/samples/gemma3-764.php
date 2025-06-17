    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query (important: use prepared statements for security)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

      // Execute the query
      if ($stmt->execute()) {
        // Get result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $stmt->close();  // Close the statement
          header("Location: welcome.php"); // Redirect to a welcome page
          exit();
        } else {
          $error_message = "Invalid username or password.";
        }
      } else {
        $error_message = "Error executing query.";
      }
    } else {
      $error_message = "Error preparing query.";
    }
  }
}
?>
