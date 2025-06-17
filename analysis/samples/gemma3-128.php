
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
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
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Sanitize input here
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage:
// 1. Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 2. Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // 3. Validate input (VERY IMPORTANT - Protect against SQL injection)
  if (empty($username) || empty($password)) {
    $error[] = "Username and password cannot be empty.";
  }

  // 4. Call the login function
  $login_result = loginUser($username, $password);

  // 5. Handle the result
  if ($login_result) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    $error[] = "Invalid username or password.";
  }
}
?>
