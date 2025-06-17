
<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a secure page or display a welcome message
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
    } else {
        // Display an error message
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}


?>
