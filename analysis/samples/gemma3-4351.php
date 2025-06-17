


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to register a new user
function registerUser($username, $email, $password) {
    // 1. Validate the inputs
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        return "Error: All fields are required.";
    }

    // Check if username is already registered
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['host'], $sql);

    if (mysqli_num_rows($result) > 0) {
        return "Error: Username already exists.";
    }

    // Check if email is already registered
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($GLOBALS['host'], $sql);

    if (mysqli_num_rows($result) > 0) {
        return "Error: Email already exists.";
    }

    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. Insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['host'], $sql)) {
        return "User registered successfully!";
    } else {
        return "Error: " . mysqli_error($GLOBALS['host']);
    }
}

// --- Example Usage (for demonstration only - this will be handled by the form submission) ---
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST["username"];
//     $email = $_POST["email"];
//     $password = $_POST["password"];

//     $registration_result = registerUser($username, $email, $password);
//     echo $registration_result; // This would typically be displayed to the user
// }
?>
