
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

  <p><a href="logout.php?logout=1">Logout</a></p> <!-- Link to logout page -->
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Form handling - Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Crucial for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) { // Example email validation
        $error = "Invalid email format.";
    } else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        } else {
            // If login is successful, set session variables
            session_start();
            $_SESSION["username"] = $username;
            $error = "Login successful!";
            // Redirect to a secure page or display a welcome message
            header("Location: secure_page.php"); // Replace with your secure page
            exit();
        }
    }
}
?>
