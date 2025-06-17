
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter username" required>
    <br><br>
    Password: <input type="password" name="password" placeholder="Enter password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to select the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return null;
  }

  // Close connection
  $conn->close();
}

// Example usage (This is for demonstration - don't directly use this in a production environment)
// You'll need to replace these with your actual username and password.
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $user = loginUser($username, $password);

  if ($user) {
    // User logged in successfully
    echo "Login successful!";
    echo "<br>Welcome, " . $user['username'];

    // You can store the user's ID in a session or cookie for further use.
    // Example (using session):
    session_start();
    $_SESSION['user_id'] = $user['id'];
    
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}
?>
