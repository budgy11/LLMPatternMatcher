
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (basic, can be expanded)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        }

        if ($result->num_rows > 0) {
            // User found, set session variables
            $_SESSION["username"] = $username;
            // You might want to redirect after login
            header("Location: welcome.php");
            exit();
        }
    }
}


// Start the session
session_start();

?>
