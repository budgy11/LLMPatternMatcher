

<?php

// Database credentials (replace with your actual credentials)
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

  // SQL query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
      // Login successful
      $_SESSION['loggedin'] = true;
      $_SESSION['id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}


// Example usage (HTML form for login)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: protected.php"); // Replace 'protected.php' with your protected page URL
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>
