

<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username;
    echo "Login successful!";
    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page's URL
    exit();
  } else {
    echo "Invalid username or password.";
  }

  $conn->close();
}
?>
