

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling (for simplicity, we'll use GET for this example.  In a real application, you'd use POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validation -  Important to prevent security vulnerabilities
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (preg_match("/@/", $email)) {
        $error = "Invalid email format.";
    } else {
        //  Password hashing - VERY IMPORTANT for security!
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

        if (mysqli_query($GLOBALS["conn"], $sql)) {
            // Registration successful
            echo "Registration successful! Please check your email to verify.";
        } else {
            // Registration failed
            echo "Registration failed: " . mysqli_error($GLOBALS["conn"]);
        }
    }
}

// Display the registration form
?>
