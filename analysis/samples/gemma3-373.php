

<?php

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to select the user
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    return false; // User not found
  }

  $user = $result->fetch_assoc();

  // Verify the password
  if (password_verify($password, $user['password'])) {
    // If passwords match, set session variables
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $username;
    return true; // Login successful
  } else {
    return false; // Incorrect password
  }

  $conn->close();
}

// Example usage (to demonstrate login)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace protected_page.php
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
