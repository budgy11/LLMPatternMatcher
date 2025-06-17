
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input data
    $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate the password (basic example - you should use stronger password validation)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($GLOBALS['conn'], $sql);

    if (mysqli_num_rows($result) > 0) {
        $email_error = "Email already exists.";
    }

    // Hash the password (important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If validation passes, insert the user into the database
    if (!empty($email_error) || !empty($password_error)) {
        $errors = $email_error . " " . $password_error;
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

        if (mysqli_query($GLOBALS['conn'], $sql)) {
            echo "Registration successful! Please check your email to verify your account.";
        } else {
            echo "Error: " . mysqli_error($GLOBALS['conn']);
        }
    }
}
?>
