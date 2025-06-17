    Username: <input type="text" name="username" placeholder="Enter your username" />
    Password: <input type="password" name="password" placeholder="Enter your password" />
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (VERY IMPORTANT - prevents SQL injection)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query -  IMPORTANT: Use prepared statements for security!
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ss", $username, $password); // "ss" specifies string parameters
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found
        session_start();
        $_SESSION["user_id"] = $result->fetch_assoc()["id"];
        $_SESSION["username"] = $username;
        echo "Login successful!";
        header("Location: /welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    } else {
      $error = "Error preparing SQL statement.";
    }
  }
}

// Start the form
?>
