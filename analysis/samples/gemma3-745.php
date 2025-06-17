
    <form action="" method="post">
        Username: <input type="text" name="username" required>
        <br><br>
        Password: <input type="password" name="password" required>
        <br><br>
        <button type="submit">Login</button>
    </form>

    <br><br>
    <a href="register.php">Don't have an account? Register here.</a>

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

    // Validate input (basic)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepared statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        }

        if ($result->num_rows > 0) {
            // User found, set session variables
            $_SESSION["username"] = $username;
            header("Location: welcome.php");
            exit();
        }

        $stmt->close(); // Close the statement
    }
}

session_start();

?>
