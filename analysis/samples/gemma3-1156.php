

<?php

// Database credentials - Replace with your actual values
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Fetch the first row as an associative array
    return $user; // Return user data if login is successful
  } else {
    return false; // Return false if login fails
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (Demonstration)

// Get username and password from form (or wherever you get them)
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function
$loggedInUser = loginUser($username, $password);

// Check if the login was successful
if ($loggedInUser) {
  // User is logged in - Now you can redirect to a different page,
  // display a welcome message, or access user data.
  echo "Welcome, " . $loggedInUser['username'] . "!";
  // You can also do things like:
  // session_start();
  // $_SESSION['user_id'] = $loggedInUser['id']; // Example: Store user ID in session
} else {
  // Login failed
  echo "Invalid username or password.";
}

?>
