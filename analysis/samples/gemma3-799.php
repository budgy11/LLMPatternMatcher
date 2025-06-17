

<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "Invalid email address.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check the credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found!  Set session variables.  This is crucial.
            session_start(); // Start the session
            $_SESSION["username"] = $username; // Store the username in the session
            header("Location: welcome.php"); // Redirect to a welcome page
            exit(); // Stop further script execution
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
