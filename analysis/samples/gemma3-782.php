

<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data (from the login form)
$username = $_POST['username'];
$password = $_POST['password'];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to check credentials
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, set session variables and redirect
    session_start(); // Start the session (important!)

    // Get user data
    $user_data = $result->fetch_assoc();

    // Store user data in session (for easier access)
    $_SESSION['user_id'] = $user_data['user_id'];
    $_SESSION['username'] = $user_data['username'];

    // Redirect to a protected page (e.g., home page)
    header("Location: home.php"); // Replace 'home.php' with your protected page
    exit(); // Crucial: Exit the script after redirecting
} else {
    // User doesn't exist
    echo "Invalid username or password.";
}

$conn->close();

?>
