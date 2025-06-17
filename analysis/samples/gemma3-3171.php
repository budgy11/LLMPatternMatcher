

<?php

// Database connection details - Replace with your actual details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data to prevent security vulnerabilities
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username is empty
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    }

    // Check if password is empty
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    }

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS["conn"], $sql);
    if (mysqli_num_rows($result) > 0) {
        $username_error = "Username already exists.";
    }

    // If all validations pass, insert the user into the database
    if (empty($username_error) && empty($password_error) && empty($email_error)) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($GLOBALS["conn"], $sql)) {
            // Registration successful
            echo "Registration successful! Please log in.";
        } else {
            // Error during registration
            echo "Error: " . $sql . "<br>" . mysqli_error($GLOBALS["conn"]);
        }
    }
}
?>
