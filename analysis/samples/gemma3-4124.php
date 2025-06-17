

<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate the input data
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if the username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Check if the username or email already exists in the database
        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username or email already exists.";
        } else {
            // Hash the password using password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            if (mysqli_query($db_host, $sql)) {
                $success_message = "Registration successful! Please check your email to activate your account.";
            } else {
                $error_message = "Error inserting data into the database. " . mysqli_error($db_host);
            }
        }
    }
}
?>
