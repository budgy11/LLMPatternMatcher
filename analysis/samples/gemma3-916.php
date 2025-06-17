

<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the user exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // Authentication successful - Set a session variable
            session_start();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            // Redirect to a secure page (e.g., dashboard)
            header("Location: dashboard.php"); // Replace with your secure page
            exit();
        } else {
            // Password mismatch
            echo "<p>Incorrect password.</p>";
        }
    } else {
        // User not found
        echo "<p>User not found.</p>";
    }
}
?>
