

<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid Username (Please enter a valid email address).";
  } else {
    // SQL Query (Use prepared statements for security - see explanation below)
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    }

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // User exists, now verify the password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Password is correct, set session variables
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $row["id"]; // Store user ID for better security
        header("Location: welcome.php"); // Redirect to a welcome page
        exit;
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "User not found.";
    }
  }
}
?>
