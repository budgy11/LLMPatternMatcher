        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

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
            // Login successful - set session variables
            session_start();
            $_SESSION["username"] = $username; // Store username in session
            $conn->close();
            header("Location: welcome.php"); // Redirect to a welcome page (or your home page)
            exit();
        }
    }
}

?>
