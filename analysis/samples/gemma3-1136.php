

<?php

// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS!
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

  // SQL Query to check login
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User exists, retrieve data
    $row = $result->fetch_assoc();
    // Set session variables to track the user
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // Redirect to a protected page or display a welcome message
    return true; // Indicate successful login
  } else {
    // User does not exist
    return false; // Indicate failed login
  }

  $conn->close();
}

// Example usage (handling the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Example: Redirect to a protected page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

//  Example login form (for testing)
?>
