

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to check login credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check login
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data (you might want to return more data)
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return null;
  }

  // Close the connection
  $conn->close();
}

// Example usage (handling form submission - adjust as needed for your application)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $user = loginUser($username, $password);

  if ($user) {
    // Login successful
    session_start();
    $_SESSION["username"] = $user["username"]; // Store username in session
    echo "Login successful!  You are logged in as " . $user["username"];
    // Redirect to a protected page or display a welcome message.
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}
?>
