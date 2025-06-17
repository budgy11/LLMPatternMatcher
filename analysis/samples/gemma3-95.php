
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

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  // Execute the query
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $row['username']; // Store username in session
    $_SESSION['user_id'] = $row['id']; //  Optional: Store user ID for more robust security

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage (This would be called from a form submission)
// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  if (loginUser($username, $password)) {
    // If login is successful, redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Redirect to a secure page
    exit();
  } else {
    // If login fails, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

// Example form (This would be part of your HTML form)
?>
