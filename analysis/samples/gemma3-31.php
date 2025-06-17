    Username: <input type="text" name="username" placeholder="Enter username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Replace register.php with your registration page -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (Important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled.";
    } else {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to select the user
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        }

        if ($result->num_rows > 0) {
            // User found, set session variables
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming you have a user ID
            echo "Login successful!";
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        }
    }
}
?>
