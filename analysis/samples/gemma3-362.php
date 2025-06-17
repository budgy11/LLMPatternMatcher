
    <a href="logout.php">Logout</a> <!--  Link to a logout script -->
</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data
    $user = $result->fetch_assoc();
    return $user; // Return the user's data (you can adapt this to return just the user ID, etc.)
  } else {
    // User not found
    return null;
  }

  // Close the connection
  $conn->close();
}

// Example Usage:  (This part is for demonstration - you'll need to integrate this into your application)

// Get username and password from the form (replace with your form input names)
$username = $_POST['username'];
$password = $_POST['password'];

// Handle the login process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = loginUser($username, $password);

  if ($user) {
    // Login successful
    // Set a session variable to store the user's ID (or other relevant data)
    session_start();
    $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' field in your users table
    echo "Login successful!  You are now logged in as " . $username;
    // Redirect the user to a different page (optional)
    header("Location: welcome.php");
    exit();
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}
?>
