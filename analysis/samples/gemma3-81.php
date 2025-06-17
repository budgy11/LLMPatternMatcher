

<?php

// Database configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session
    session_start();
    $user_data = $result->fetch_assoc();
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['user_id'] = $user_data['id']; // Assuming you have a user_id column
    return true;
  } else {
    return false;
  }

  $conn->close();
}

// Example usage (handle login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Redirect to a secure page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
