

<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare the SQL query
      $sql = "SELECT id, username, password FROM users WHERE username = '$username' AND password = '$password'";

      // Execute the query
      $result = $conn->query($sql);

      // Check if any rows were returned
      if ($result->num_rows > 0) {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $user_id = $result->fetch_assoc()["id"]; // Get the user's ID
        $_SESSION["user_id"] = $user_id;
        // Redirect to a welcome page or the main page
        header("Location: welcome.php"); // Replace welcome.php with your desired redirect page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}

// If the form hasn't been submitted, display the login form
?>
