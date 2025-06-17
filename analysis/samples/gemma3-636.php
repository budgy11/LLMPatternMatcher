    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // **IMPORTANT: HASH THE PASSWORD!**
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection details (same as login.php)
    $host = "localhost";
    $username = "your_username";
    $password = "your_password";
    $database = "your_database";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();

        // Success message
        $success = "Registration successful.  Please login.";

        $stmt->close();
        $conn->close();

    } else {
        $success = "Error preparing statement.";
    }
}
?>
