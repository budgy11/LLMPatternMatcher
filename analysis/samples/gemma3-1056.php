

<?php

// Database configuration
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password cannot be empty.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Attempt to authenticate
        $result = mysqli_query(mysqli_connect($db_host, $db_user, $db_password), "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'");

        if (mysqli_num_rows($result) > 0) {
            // User authentication successful!
            session_start();
            $_SESSION["username"] = $username;
            header("Location: secure_page.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
