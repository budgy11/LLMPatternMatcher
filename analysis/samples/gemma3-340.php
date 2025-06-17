
<!-- HTML form for login -->
<form method="post" action="">
  Username: <input type="text" name="username">
  Password: <input type="password" name="password">
  <button type="submit">Login</button>
</form>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
    $error = "Invalid username.  Username can only contain letters, numbers, periods, underscores, and hyphens.";
  } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
    $error = "Invalid password. Password can only contain letters and numbers.";
  }
  else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found - set session variables to indicate login
      session_start(); // Must be called before setting session variables
      $_SESSION["username"] = $username;
      $_SESSION["user_id"] = $result->fetch_assoc()["user_id"]; // Assuming you have a user_id column

      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Replace with your protected page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

// Display the login form
?>
