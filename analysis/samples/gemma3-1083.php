

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_username";
$db_password = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, start a session
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['id'] = $row['id']; // Assuming you have an 'id' column in your users table

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example Usage:  (This would typically be part of a form submission handler)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
