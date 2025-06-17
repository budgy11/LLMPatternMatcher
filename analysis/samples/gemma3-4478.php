

<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input (Important for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic input validation
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Hash the password (Crucial for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user data
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
        // Registration successful
        $success = true;
    } else {
        // Registration failed
        $success = false;
        $error_message = "Error: " . mysqli_error($GLOBALS["conn"]);
    }
} else {
    // Form was not submitted, clear any existing errors
    $errors = [];
    $success = false;
}

// Display the registration form
?>
