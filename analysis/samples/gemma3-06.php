    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials!
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST["username"] ?? ""; // Use null coalescing operator for safety
$login_password = $_POST["password"] ?? "";


// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query -  This is a basic example - enhance with input sanitization!
$sql = "SELECT * FROM users WHERE username = '$login_username' AND password = '$login_password'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, you can now proceed with authentication
    // For example, set a session variable
    session_start(); // Important: Start session before setting session variables
    $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming 'id' is a column in your users table
    $_SESSION["username"] = $login_username;

    echo "Login successful!  Redirecting...";
    // Redirect to a secure page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your secure page URL
    exit(); // Important to prevent further script execution
} else {
    echo "Invalid username or password.";
}

$conn->close();

?>
