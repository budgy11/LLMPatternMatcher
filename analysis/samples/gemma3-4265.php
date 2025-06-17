

<?php

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Validate input (basic example - enhance with more robust validation)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (preg_match("/@/", $email)) {
        $error = "Invalid email format.";
    } else {
        // Hash the password (IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

        // Execute the query
        if (mysqli_query($GLOBALS['conn'], $sql)) {
            // Registration successful
            $success = "Registration successful! You have been logged in.";
        } else {
            // Error during registration
            $error = "Error: " . mysqli_error($GLOBALS['conn']);
        }
    }
} else {
    // Form not submitted, initialize form data
    $username = "";
    $password = "";
    $email = "";
    $error = "";
    $success = "";
}

// Connect to the database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
