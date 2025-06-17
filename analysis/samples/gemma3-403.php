
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT:  NEVER HARDCODE CREDENTIALS IN PRODUCTION!**
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic - improve for production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($host, $sql);

    if (mysqli_num_rows($result) > 0) {
      // User exists - set session variables
      // You might want to store more user data here (e.g., user_id, role)
      $_SESSION["username"] = $username; 

      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php");
      exit(); // Stop further execution after redirection
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}

?>
