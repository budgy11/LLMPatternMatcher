
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the inputs (Basic example - enhance this for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Invalid username.  Please enter a valid email or alphanumeric username.";
  } else {
    // SQL query to check the credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $query);

    if (mysqli_num_rows($result) > 0) {
      // User found, verify the password
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {
        // Password matches, login successful
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        // Redirect to a welcome page or desired location
        header("Location: welcome.php"); // Replace with your welcome page URL
        exit(); // Stop further execution
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>
