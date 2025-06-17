

<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $error_message = "Invalid username. Please use a valid email address or alphanumeric characters only.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query
    $sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

    // Execute query
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      // User found, set session variable
      session_start();
      $_SESSION["username"] = $username;
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>
