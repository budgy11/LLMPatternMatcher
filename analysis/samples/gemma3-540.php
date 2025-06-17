
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate the input
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password cannot be empty.";
    } else {
        // Query to check for the username and password
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($host, $sql);

        if (mysqli_num_rows($result) == 1) {
            // User found, set session variables and redirect
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $result->fetch_assoc()["user_id"]; // Assuming you have a user_id column
            // You can add more information to the session if needed, like user roles.
            // Redirect the user to the homepage or desired page
            header("Location: homepage.php");
            exit();
        } else {
            $error_message = "Incorrect username or password.";
        }
    }
}
?>
