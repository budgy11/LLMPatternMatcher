
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="login_username">Username:</label>
        <input type="text" id="login_username" name="login_username">
        <br><br>

        <label for="login_password">Password:</label>
        <input type="password" id="login_password" name="login_password">
        <br><br>

        <input type="submit" value="Login">
    </form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual values!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input - IMPORTANT for security
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) { // Basic email validation
    $error_message = "Invalid Username (Please enter a valid email address).";
  } else {
    // Attempt to authenticate the user
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$username'"); // Use email for authentication
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) { // Use password_verify for secure comparison
          // Successful login - Set session variables
          session_start();
          $_SESSION["username"] = $username;
          $_SESSION["user_id"] = $row["id"]; // Store user ID (good practice)
          header("Location: welcome.php"); // Redirect to welcome page
          exit(); // Important: Stop further execution
        } else {
          $error_message = "Incorrect password.";
        }
      }
    } else {
      $error_message = "Query error.";
    }
  }
} else {
  // If form was not submitted, display the login form
  $error_message = ""; // Clear any previous errors
}
?>
