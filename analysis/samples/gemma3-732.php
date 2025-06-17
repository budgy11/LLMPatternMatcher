
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
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

  // SQL query to retrieve user's password from the database
  $sql = "SELECT password FROM users WHERE username = '$username'";

  // Execute the query
  $result = $conn->query($sql);

  // Check if a user with the given username exists
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stored_password = $row['password'];

    // Verify the password
    if (password_verify($password, $stored_password)) {
      // Authentication successful, set session variables
      session_start();
      $_SESSION['username'] = $username;
      return true;
    } else {
      return false;
    }
  } else {
    return false; // User not found
  }

  // Close the connection
  $conn->close();
}

// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  if (loginUser($username, $password)) {
    // Login successful, redirect to a secure page or display a welcome message
    header("Location: /secure_page.php"); // Replace /secure_page.php with your desired secure page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
