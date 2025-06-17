    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $row['password'])) {
        // Login successful
        // Set session variables
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;

        // Redirect to a welcome page or your desired location
        header("Location: welcome.php"); // Replace welcome.php with your welcome page
        exit();

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
