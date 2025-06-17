

<?php

// Database Credentials (Replace with your actual credentials)
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

  // SQL query to select the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  // Always use prepared statements in a real application!

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - Create a session
    session_start();  // Start the session (must be done before any output)

    $user = $result->fetch_assoc(); // Fetch the user data as an associative array
    $_SESSION['username'] = $user['username'];
    $_SESSION['id'] = $user['id']; // Assuming you have an 'id' column in your table
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example Usage: (This would typically be on a form submission)
// $username = $_POST['username'];
// $password = $_POST['password'];
// if (loginUser($username, $password)) {
//   // Redirect to a secure page after successful login
//   header("Location: /protected_page.php"); // Replace with your secure page URL
//   exit();
// } else {
//   echo "Invalid username or password.";
// }


?>
