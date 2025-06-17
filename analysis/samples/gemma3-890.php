

<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (Crucial for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Query the database to check credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Login successful
            // Set session variables to track the user
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = mysqli_fetch_assoc($result)['id']; // Get user ID
            header("Location: welcome.php"); // Redirect to a welcome page
            exit(); // Important: Stop further script execution
        } else {
            // Login failed
            $error_message = "Invalid username or password.";
        }
    }
}
?>
