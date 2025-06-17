

<?php
session_start(); // Start the session

// Database credentials (Replace with your actual credentials!)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Login Form Data
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL Query to fetch user data
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, verify password
    $user_data = $result->fetch_assoc();
    if (password_verify($password, $user_data['password'])) {
        // Password correct!
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_data['id']; // Store user ID for easier access
        header("Location: /welcome.php"); // Redirect to a welcome page or desired location
        exit(); // Important: Stop further script execution after redirection
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
