    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database connection details
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) { // Validate email (example)
    $error_message = "Invalid username (must be an email address).";
  } else {
    // Database query
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ss", $username, $password); // "ss" means two strings
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found
        $row = $result->fetch_assoc();
        // Set session variables
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        echo "Login successful!";
        header("Location: welcome.php"); // Redirect to a welcome page
        exit;
      } else {
        $error_message = "Incorrect username or password.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>
