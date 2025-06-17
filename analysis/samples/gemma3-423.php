

<?php
// ... (Database configuration as before)

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
    $row = $result->fetch_assoc();

    // Hash the provided password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($hashed_password === $row["password"]) {
        // Login successful
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;

        header("Location: dashboard.php");
        exit;

    } else {
        echo "Incorrect password.";
    }

} else {
    echo "User not found.";
}

$conn->close();
?>
