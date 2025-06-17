
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to handle the login process
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if a user was found
    if ($user) {
      // Authentication successful
      // You can set session variables here to store user information
      session_start(); // Start the session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      return true; // Login successful
    } else {
      return false; // User not found
    }
  } else {
    // Query execution failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (from a form submission)
// Assume username and password are submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to the dashboard or a welcome page
    header("Location: dashboard.php"); // Replace with your dashboard URL
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}
?>
