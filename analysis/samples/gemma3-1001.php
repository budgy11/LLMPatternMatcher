
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual details!
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the login data from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (basic - you should enhance this!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match("/^[_a-z0-9]+$/i", $username)) {
        $error = "Invalid username.  Must be a valid email address or contain only alphanumeric characters.";
    } else {
        // SQL query to check for the username and password
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($servername, $sql);

        if (mysqli_num_rows($result) > 0) {
            // User found, set session variables
            $_SESSION["username"] = $username;
            // Redirect to a secure page (e.g., homepage)
            header("Location: homepage.php");
            exit(); // Important: Stop further script execution
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
