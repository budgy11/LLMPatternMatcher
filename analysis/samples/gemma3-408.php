

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

// Prepare the SQL query
$sql = "SELECT id, username, password FROM users WHERE username = ?";  // Use a placeholder
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("s", $username); // "s" indicates a string

// Execute the query
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, fetch the password hash
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"];

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password matches, user is authenticated
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        echo "Login successful!";
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "User not found!";
}

$stmt->close();
$conn->close();

?>
