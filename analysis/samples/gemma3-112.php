

<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password must be filled.";
    } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
        $error_message = "Invalid username.  Only letters and numbers allowed.";
    }
    // Basic password check (change this to your desired security policy)
    elseif ($password != "password123") { // Replace with your secure password validation logic
        $error_message = "Incorrect password.";
    } else {
        // Perform the login query
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            // User found, set session variables
            session_start();
            $_SESSION["username"] = $username;
            // You could also store other user data if needed.

            // Redirect the user to the logged-in area
            header("Location: welcome.php"); // Replace welcome.php with your welcome page
            exit();
        } else {
            $error_message = "Incorrect username or password.";
        }
    }
}
?>
