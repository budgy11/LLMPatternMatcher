        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <br>
    <a href="register.php">Don't have an account? Register here.</a>


</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Database query
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check the credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        }

        if ($result->num_rows > 0) {
            // Successful login -  Set a session variable to track the user
            session_start();
            $_SESSION["username"] = $username; // Store username in session
            // You could also store other user data if needed.
            echo "Login successful!  You are now logged in.";
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        }
    }
}
?>
