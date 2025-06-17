

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form data
$login_username = $_POST['username'];
$login_password = $_POST['password'];

// 1. Validate input (important for security)
if (empty($login_username) || empty($login_password)) {
    $error = "Username and password cannot be empty.";
}

// 2. Database Query (using prepared statements for security)
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the query
$sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

// Bind parameters to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login_username, $login_password);

// Execute the query
if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, set session variables
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect the user
        header("Location: welcome.php"); // Replace with your welcome page
        exit;
    } else {
        $error = "Invalid username or password.";
    }
} else {
    $error = "Query error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Display error message (if any) - This is crucial for debugging
if ($error != "") {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
}

?>
