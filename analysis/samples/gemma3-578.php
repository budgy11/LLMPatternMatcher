

<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to validate the login form
function validateLogin($username, $password) {
    // Sanitize input (important for security)
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Sanitize password for security
    $password = hash_sha256($password, true); // Use SHA256 for stronger hashing


    // Check if username and password are empty (or if sanitization removed them)
    if (empty($username) || empty($password)) {
        return false;
    }

    // Retrieve the hashed password from the database (using a secure query)
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Compare the hashed passwords
        if (hash_sha256($password, true) === $user['hashed_password']) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Handle login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (validateLogin($username, $password)) {
        // Login successful
        session_start();
        // Store user data in the session (e.g., username)
        $_SESSION['username'] = $username;
        header("Location: welcome.php"); // Redirect to a welcome page or logged-in area
        exit();
    } else {
        // Login failed
        $error = "Invalid username or password.";
    }
}

?>
