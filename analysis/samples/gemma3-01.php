
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
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
        }

        if ($result->num_rows > 0) {
            // User found, set session variables for authentication
            $_SESSION["username"] = $username;
            echo "Login successful!";
            // Redirect to a protected page or dashboard
            header("Location: /dashboard.php");  // Replace with your protected page
            exit();
        }
    }
}

// Display the login form if no form has been submitted
?>
