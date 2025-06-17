

<?php

// Database credentials - REPLACE with your actual credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) { // Validate email format
    $error = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL Query (adjust for your table structure)
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // User found, verify password
      $user_data = $result->fetch_assoc();
      if (password_verify($password, $user_data["password"])) { // Use password_verify
        // Successful login, set session variables
        session_start();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $user_data["username"];
        echo "Login successful! <a href='welcome.php'>Welcome, " . $_SESSION["username"] . "</a>";
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>
