
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (basic validation - improve as needed)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database query
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query
        $sql = "SELECT id, username, password FROM users WHERE username = '$username' AND password = '$password'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, set session variables
            session_start();
            $_SESSION["username"] = $username; // Store username in session
            // Optionally store other user data in the session

            // Redirect to a secure page or display a success message
            header("Location: welcome.php"); // Replace welcome.php with your desired redirect
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
