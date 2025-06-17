    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (Important for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid username. Please enter a valid email address.";
    } else {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL Query (Replace with your actual table and column names)
        $sql = "SELECT * FROM users WHERE email = '$username'"; // Assuming email is the username
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                // Password correct, login successful
                $_SESSION["user_id"] = $row["id"]; // Store user ID in session
                $_SESSION["username"] = $row["email"]; // Store username in session
                header("Location: welcome.php"); // Redirect to a welcome page
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found.";
        }
    }
}
?>
