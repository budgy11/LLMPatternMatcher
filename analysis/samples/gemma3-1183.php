
<!-- HTML form (Example - adapt to your design) -->
<form method="post" action="">
  Username: <input type="text" name="username">
  Password: <input type="password" name="password">
  <input type="submit" value="Login">
</form>


<?php

// Database connection details - Replace with your actual credentials
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

  // SQL query to retrieve the password hash from the database
  $sql = "SELECT password_hash, user_id FROM users WHERE username = '$username'";  // Assuming 'username' is the user's login identifier
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Authentication successful
      session_start(); // Start the session for user tracking
      $_SESSION['user_id'] = $userId; // Store the user's ID in the session
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

// Example usage (Illustrative - Replace with your form handling logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a secure page, show a welcome message, etc.
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
