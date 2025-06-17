    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch user data
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, verify password
    $user_data = $result->fetch_assoc();
    if (password_verify($password, $user_data['password'])) {
        // Password verified, set session variables
        session_start();
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];

        // Redirect to a protected page
        header("Location: /protected_page.php"); // Replace with your protected page
        exit();
    } else {
        // Password does not match
        echo "Incorrect password.";
    }
} else {
    // User not found
    echo "User not found.";
}

$conn->close();

?>
