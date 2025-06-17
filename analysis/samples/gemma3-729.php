
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the password hash for the given username
  $sql = "SELECT password_hash, salt FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql); // Use prepared statement for security
  $stmt->bind_param("s", $username); // "s" indicates a string parameter

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return false; // User not found
  }

  $row = $result->fetch_assoc();

  // Verify the password
  $hashed_password = $row['password_hash'];
  $salt = $row['salt'];

  if (password_verify($password, $hashed_password)) {
    // Password is correct.  You can store session information here, 
    // such as user ID or username, to identify the logged-in user.
    // Example:
    session_start();
    $_SESSION['user_id'] = $row['id']; // Assuming 'id' is the user ID column
    $_SESSION['username'] = $username; 
    return true;
  } else {
    return false; // Password incorrect
  }

  $stmt->close();
  $conn->close();
}

// Example Usage (to demonstrate)
// This is just an example - you'll need to integrate this into your login form.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    echo "Login successful!";
    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
