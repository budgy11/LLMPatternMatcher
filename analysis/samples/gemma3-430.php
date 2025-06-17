
<!-- (Same HTML form as login.php) -->


<?php

// Database Credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify password
    $user_data = $result->fetch_assoc();
    if (password_verify($password, $user_data['password'])) {
      // Password matches, login successful
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      return true;
    } else {
      // Password does not match
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// --- Example Usage (Illustrative - Not complete login form) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a welcome page or desired location
    header("Location: welcome.php"); // Replace welcome.php with your desired page
    exit();
  } else {
    // Login failed, display an error message
    $error = "Invalid username or password.";
  }
}

// --- Login Form (Simplified for Demonstration) ---
?>
