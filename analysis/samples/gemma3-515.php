
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
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password are required.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (Please enter a valid email address).";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Assuming email as the login field
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // User found, verify password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Password correct, set session variables
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["email"]; // Or whatever your username field is

        // Redirect to a welcome page or your main application
        header("Location: welcome.php");
        exit(); // Important to stop further script execution
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "No user found with this email address.";
    }
  }
}
?>
