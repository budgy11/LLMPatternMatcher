

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to authenticate the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, fetch the password hash
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"]; // Assuming password is stored as a hash

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password matches, user is authenticated
        // Set a session variable to store the user's ID
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        echo "Login successful!";  // Or redirect to your dashboard
        // You can also set a flag to show a success message on the login form
        // e.g., echo "<p style='color:green;'>Login successful!</p>";

    } else {
        // Password does not match
        echo "Incorrect password!";
    }
} else {
    // User does not exist
    echo "User not found!";
}

$conn->close();

?>
