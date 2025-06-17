

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to select user based on username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, retrieve data
    $user = $result->fetch_assoc();
    // Set session variables to store user information
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // You might want to add more session variables here, like user role etc.
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage:
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a different page or display a welcome message
    echo "Login successful!  Redirecting...";
    header("Location: welcome.php");  // Redirect to a welcome page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
