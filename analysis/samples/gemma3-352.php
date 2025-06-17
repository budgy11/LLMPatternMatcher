    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Basic validation - enhance for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Use a prepared statement to prevent SQL injection
      $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ss", $username, $password);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $result->fetch_assoc(); // Fetch the row data
          $error = "";  // Clear any previous errors
          header("Location: welcome.php");
          exit();
        } else {
          $error = "Incorrect username or password.";
        }

        // Close the statement
        $stmt->close();
      } else {
        $error = "Error preparing statement.";
      }
    }
  }
}

?>
