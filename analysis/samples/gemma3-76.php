
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Register</a>  <!--  Link to a registration page -->
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security - prevents SQL injection)
    if (empty($username) || empty($password)) {
        $error[] = "Username and Password must be filled.";
    }

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
        // User found, set session variables to store user information
        $_SESSION["username"] = $username; // Store username in the session
        // You can store other user data in the session as needed (e.g., user_id, role)

        // Redirect to a secure page (e.g., the main page)
        header("Location: welcome.php");  //  Replace welcome.php with your secure page
        exit(); // Important to stop further execution
    } else {
        $error[] = "Invalid username or password.";
    }

    // Close the database connection
    $conn->close();
}
?>
