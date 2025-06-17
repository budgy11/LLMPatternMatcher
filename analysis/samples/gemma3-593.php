

<?php

// Database credentials (replace with your actual values)
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

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data (you can customize this to return other user details)
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - see notes below)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Call the loginUser function
    $user = loginUser($username, $password);

    if ($user) {
      // User logged in successfully
      // You can redirect to a welcome page or do something else here
      // echo "Welcome, " . $user['username'];  // Display username (example)
      // For production, you'd typically set a session variable here.
      session_start();
      $_SESSION['username'] = $username;  // Store username in session
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();

    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}

?>
