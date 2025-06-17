
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <form method="post" action="">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    // You can also store other user information in session if needed
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - see notes below)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful, redirect to a protected page or display a welcome message
      header("Location: protected_page.php"); // Redirect to a secure page
      exit();
    } else {
      // Login failed, display an error message
      echo "Invalid username or password.";
    }
  }
}

?>
