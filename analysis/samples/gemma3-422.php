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

// Form data
$username = $_POST["username"];
$password = $_POST["password"];


// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve user data
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row["password"])) { // Use password_verify()
        // Login successful
        session_start();  // Start the session
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php"); // Replace with your dashboard URL
        exit;

    } else {
        // Incorrect password
        echo "Incorrect password.";
    }

} else {
    // User not found
    echo "User not found.";
}

$conn->close();

?>
