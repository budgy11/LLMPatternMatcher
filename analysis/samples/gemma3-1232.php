
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual database details)
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

  // SQL query to check credentials
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    // User found, verify password
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Password matches, login successful
      // You can store user session data here (e.g., set session variables)
      // Example:
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true;
    } else {
      // Password mismatch
      return false;
    }
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (for demonstration - NOT for production!)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed - Display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
