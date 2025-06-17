
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
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

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
    $error = "Invalid username.  Please use alphanumeric characters.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) {
    $error = "Invalid password. Please use alphanumeric characters.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to check credentials
      $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password (using password_hash or similar)
        if (password_verify($password, $row["password"])) {
          // Login successful!
          session_start();
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $row["username"];
          $error = "Login successful!";  // Optional: Redirect or display success message
          header("Location: welcome.php");  // Redirect to a welcome page
          exit();
        } else {
          $error = "Incorrect password.";
        }
      } else {
        $error = "Incorrect username.";
      }
    }
  }
}
?>
