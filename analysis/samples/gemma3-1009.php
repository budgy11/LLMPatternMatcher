
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT: Replace with your actual credentials**
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the username and password inputs
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate the inputs (basic validation -  stronger validation recommended)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // Query the database to check for the username and password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db_host, $query);

    if (mysqli_num_rows($result) > 0) {
      // User found - set session variables
      $_SESSION["username"] = $username;  // Store username in session
      // You could also store other user data here if needed.
      // For example: $_SESSION["user_id"] = $result[0]["id"];

      // Redirect to a protected page
      header("Location: protected_page.php"); // Replace with your protected page URL
      exit(); // Terminate the script execution
    } else {
      $error_message = "Incorrect username or password.";
    }
  }
}

// Function to display the login form
function displayLoginForm() {
  ?>
