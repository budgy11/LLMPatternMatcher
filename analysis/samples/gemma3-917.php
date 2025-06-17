
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Basic - can be improved for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // Check the password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Login successful
        // You can store the user ID in a session or cookie
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        echo "Login successful!";
        // Redirect to a protected page or display a welcome message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit;
      } else {
        $error_message = "Incorrect password.";
      }
    }
  }
}
?>
