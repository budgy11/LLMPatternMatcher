
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h1>User Login</h1>

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

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Form handling (for simplicity, we'll use GET.  Consider POST for security)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (VERY IMPORTANT - sanitize and validate all user input!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to check credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, set session variables
            $_SESSION["username"] = $username;  // Store username in session
            $conn->close(); // Close connection

            // Redirect the user
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}

// Check if the session is already set (e.g., if the user came from a logout)
if (isset($_SESSION["username"])) {
    header("Location: welcome.php");
    exit();
}
?>
