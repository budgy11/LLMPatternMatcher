

<?php

// Database configuration (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Important: Use prepared statements for security!

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $result->fetch_assoc()["id"];  // Assuming you have an 'id' column in your users table

    // Redirect the user to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Invalid credentials
    echo "<p>Invalid username or password.</p>";
  }

  $conn->close();
}

?>
