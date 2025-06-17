

<?php

// Database credentials (Replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Form data
$login_username = $_POST['username'];
$login_password = $_POST['password'];

// Connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "SELECT id, username, password FROM users WHERE username = '$login_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  // Password verification
  if (password_verify($login_password, $row['password'])) {
    // Login successful
    // Set session variables
    session_start();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // Redirect the user
    header("Location: welcome.php"); // Replace with your welcome page
    exit;
  } else {
    // Incorrect password
    echo "Incorrect password.";
  }
} else {
  // User not found
  echo "User not found.";
}

$conn->close();

?>
