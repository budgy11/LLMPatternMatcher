

<?php

// Database credentials - Replace with your actual credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (IMPORTANT -  DO NOT use this simple validation in a production environment!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Query the database to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($host, $sql);

    if (mysqli_num_rows($result) > 0) {
      // User found - set session variables and redirect
      session_start();
      $_SESSION["username"] = $username;
      // You might want to store other user data in the session as well
      //  (e.g., user_id, role, etc.)
      //  Example:  $_SESSION["user_id"] = $result[0]["id"];
      //  Redirect the user to the protected page
      header("Location: /protected_page.php"); // Replace with the URL of your protected page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
