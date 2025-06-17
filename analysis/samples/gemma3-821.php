    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the form data
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Sanitize the input (important for security!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate the input (basic example - you should add more robust validation)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL Query -  **IMPORTANT:  Use prepared statements to prevent SQL injection!**
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found - Set session variables
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];

        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php");
        exit(); // Stop further execution
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error executing query.";
    }
  }
}
?>
