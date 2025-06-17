

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Query the database to check for the username and password
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db_host, $sql); // Use mysqli_query for modern PHP

    if (mysqli_num_rows($result) > 0) {
      // User found, set a session variable to indicate login
      $_SESSION["username"] = $username;
      // Optionally, redirect to a protected page after successful login
      header("Location: protected_page.php"); // Replace with your protected page
      exit(); // Important to stop further script execution
    } else {
      $error_message = "Incorrect username or password.";
    }
  }
}
?>
