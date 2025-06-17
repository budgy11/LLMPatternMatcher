
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

  // SQL query to select user based on username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, retrieve user data
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userName = $user['username'];

    // Set session variables for the logged-in user
    session_start();
    $_SESSION['userId'] = $userId;
    $_SESSION['userName'] = $userName;

    // You can also store other user data in the session
    // $_SESSION['userEmail'] = $user['email'];

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage (assuming you have a form to submit username and password)
//  This is just an example, you'll need to integrate it with your form
//  and error handling.

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!loginUser($username, $password)) {
    $error = "Invalid username or password.";
  } else {
    // If login is successful, redirect to the user's dashboard or main page
    header("Location: dashboard.php"); // Replace with your desired redirect
    exit();
  }
}


?>
