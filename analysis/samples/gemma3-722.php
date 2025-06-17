
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


// ... (Database connection code from above) ...

// SQL query with placeholders
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters (important!)
$stmt->bind_param("ss", $username, $password); // "ss" means two strings

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check the result
if ($result->num_rows > 0) {
    // ... (Process the result) ...
} else {
    // ... (User not found) ...
}

// Close the statement
$stmt->close();


<?php

// Database credentials (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_pass = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    // User found - Set session variables to indicate login
    session_start();
    $_SESSION['username'] = $username;
    return true;
  }

  // Close the connection
  $conn->close();
}

// Example Usage:
// 1. Form Submission (Simplified example - replace with your HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Successful login - Redirect to a protected page or display a welcome message
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed - Display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
