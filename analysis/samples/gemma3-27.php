
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Query to retrieve the user's data
  $query = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "'";
  $result = $conn->query($query);

  // 3. Check if the user exists and the password matches
  if ($result->num_rows == 0) {
    return false; // User doesn't exist
  }

  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    // 4. User exists and password matches
    $_SESSION['username'] = $user['username']; // Store username in session
    return true;
  } else {
    return false; // Password does not match
  }

  // 5. Close the connection
  $conn->close();
}


// Example usage (login form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful
    header("Location: welcome.php"); // Redirect to a welcome page (replace with your URL)
    exit();
  } else {
    // Login failed
    $error = "Invalid username or password.";
  }
}


// Login form (HTML)
?>
