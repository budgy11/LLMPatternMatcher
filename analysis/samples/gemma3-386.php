    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Form data handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
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

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        } else {
            // Successful login
            session_start();  // Start a session to store user information
            $_SESSION["username"] = $username;  // Store username in session
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        }
    }
}
?>
