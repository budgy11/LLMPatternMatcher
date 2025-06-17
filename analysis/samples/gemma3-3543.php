

<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Form handling (for simplicity, let's assume a simple form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validate the data (Crucial for security)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (preg_match('/@/', $email)) {  // Check for invalid email format
        $error = "Invalid email format.";
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

        // Execute the query
        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success = "Registration successful!  You have been logged in.";
        } else {
            $error = "Error registering user.  " . mysqli_error($GLOBALS['conn']);
        }
    }
} else {
    // If this is not a POST request, initialize the form
    $username = "";
    $password = "";
    $email = "";
    $error = "";
    $success = "";
}


// Connect to the database
$GLOBALS['conn'] = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}



?>
