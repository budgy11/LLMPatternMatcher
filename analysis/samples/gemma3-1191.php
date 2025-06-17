

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $query = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }
}


// Example Usage:

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - add more robust validation here)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Call the login function
    $loginResult = loginUser($username, $password);

    if ($loginResult == true) {
      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Change to your protected page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

?>
