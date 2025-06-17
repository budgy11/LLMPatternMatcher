

<?php

// Database Credentials (Replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get input values
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Database connection
  $conn = new mysqli($servername, $username, $password, $database);

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
      // Password verification successful, login user
      // Set session variables to store user information
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect to a secure page or display a success message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();

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

// HTML form for login
?>
