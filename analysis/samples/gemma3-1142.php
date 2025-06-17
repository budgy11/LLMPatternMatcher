

<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
    $error_message = "Invalid username. Please use only alphanumeric characters.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) {
    $error_message = "Invalid password.  Please use only alphanumeric characters.";
  }

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    $conn->close();

    // Redirect to a protected page or display a success message
    header("Location: secure_page.php"); // Redirect to a secure page.
    exit();

  } else {
    // User not found
    $conn->close();
    $error_message = "Invalid username or password.";
  }
}
?>
