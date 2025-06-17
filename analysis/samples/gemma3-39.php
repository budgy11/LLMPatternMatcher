

<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, verify password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      // Password verification successful, login the user
      // Set session variables for authentication
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $username;

      // Redirect to a secure page or display a welcome message
      header("Location: welcome.php"); // Replace welcome.php with your welcome page
      exit(); // Important: Stop further script execution
    } else {
      // Password mismatch
      echo "<p style='color: red;'>Incorrect password.</p>";
    }
  } else {
    // User not found
    echo "<p style='color: red;'>User not found.</p>";
  }

  $conn->close();
}

?>
