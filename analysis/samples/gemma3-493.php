
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information based on username
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password (using password_verify)
    if (password_verify($password, $row['password'])) {
      // Successful login
      $_SESSION['user_id'] = $row['id']; // Store user ID in session
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example Usage (Form Handling - Simplified)
// Assume this is from a form submission

// Get username and password from form data (in a real application, sanitize this!)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the login function returns true
if (loginUser($username, $password)) {
  // Successful Login - Redirect to protected page or display welcome message
  header("Location: /protected_page.php"); // Replace with the correct URL
  exit();
} else {
  // Failed Login - Display error message
  echo "<p style='color:red;'>Invalid username or password.</p>";
}

?>
