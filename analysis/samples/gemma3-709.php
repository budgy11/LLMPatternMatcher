    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (basic)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    }
    // TODO: Add more robust input validation here

    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }

    if (empty($error)) {
        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection (same as login.php)
        $host = "localhost";
        $username = "your_db_username";
        $password = "your_db_password";
        $database = "your_db_name";

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL Query
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        $result = $conn->query($sql);

        if ($result) {
            $success = "Registration successful! Please log in.";
        } else {
            $error = "Registration failed: " . $conn->error;
        }

        $conn->close();
    }
}
?>
