

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Important for security - prevent SQL injection)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password (for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check for the username and password
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found - set session variables
      session_start();
      $_SESSION["username"] = $username;
      $result->fetch_assoc(); // Get the user data
      $user_data = $result->fetch_assoc();
      $_SESSION["user_id"] = $user_data["id"]; // Assuming you have a user_id field
      
      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php");
      exit(); 

    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>
