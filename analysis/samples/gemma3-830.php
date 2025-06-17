        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <br>
    <a href="register.php">Don't have an account? Register here.</a>
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Login Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate Input (Important for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password cannot be empty.";
    } else {
        // Query to Check User Credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            // User found, set session variables
            $_SESSION["username"] = $username;
            $redirect_url = "welcome.php"; // Or your desired page after login
            header("Location: $redirect_url");
            exit(); // Stop further script execution
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
