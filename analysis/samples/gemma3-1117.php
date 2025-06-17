

<?php

// Database credentials (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables for authentication
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    // You might want to store other user information here if needed
    
    // Redirect to a secure page
    header("Location: secure_page.php"); // Replace 'secure_page.php' with the appropriate URL
    exit();
  } else {
    // User not found
    $error = "Invalid username or password.";
  }
}
?>
