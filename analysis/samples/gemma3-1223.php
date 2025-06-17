

<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// 1. Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 2. Prepare the SQL query
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

// Execute the query
$stmt->execute();

// 3. Get the result
$result = $stmt->get_result();

// 4. Process the result
$row = $result->fetch_assoc();

if($row) {
  // User exists
  // Set session variables
  session_start(); // Important: Start the session
  $_SESSION['username'] = $username;
  $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table

  // Redirect the user to a protected page
  header("Location: /protected_page.php"); // Replace with your protected page URL
  exit(); // Important: Stop further execution
} else {
  // User doesn't exist
  echo "Invalid username or password.";
}

// 5. Close the connection
$stmt->close();
$conn->close();

?>
