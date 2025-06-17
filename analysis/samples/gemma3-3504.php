

<?php

// Database Configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Password validation (Simple example - consider more robust validation)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } else if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if username is already taken
    $username_exists = false;
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT username FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $username_exists = true;
    }

    $conn->close();


    // If everything is valid, insert the user into the database
    if (empty($username) || empty($email) || empty($password) || $username_exists) {
        $error = "Error: Please check your information.";
    } else {
        // Hash the password (IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if ($conn = new mysqli($db_host, $db_user, $db_password, $db_name)) {
            if ($conn->query($sql)) {
                $success = "Registration successful! Please login.";
            } else {
                $error = "Error: " . $conn->error;
            }
            $conn->close();
        } else {
            $error = "Connection failed.";
        }
    }
}
?>
