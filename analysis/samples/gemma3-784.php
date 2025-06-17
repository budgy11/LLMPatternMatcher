
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="login.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
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

  // SQL query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch the user data as an associative array

    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Password is correct, login successful
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      return true; // Login successful
    } else {
      // Password mismatch
      return false; // Login failed
    }
  } else {
    // User not found
    return false; // Login failed
  }

  // If any of the above steps fail, disconnect
  $conn->close();
  return false;
}


// --- Example Usage (for testing) ---
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
