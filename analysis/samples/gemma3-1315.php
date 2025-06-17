

<?php

// Configuration - Replace with your database credentials and email settings
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$from_email = 'your_email@example.com';
$from_name = 'Your Website Name';


// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Sanitize and Validate Inputs
    $email = trim($email); // Remove leading/trailing whitespace
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }
    $new_password = trim($new_password);
    if (empty($new_password)) {
        return "Password cannot be empty.";
    }

    // 3. Hash the new password (Important for Security)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        return "Password reset successfully.  Please check your email.";
    } else {
        return "Error resetting password. " . $conn->error;
    }

    // 5. Close the connection
    $conn->close();
}


// Example Usage (This is just for testing, integrate this into your form)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    $reset_message = forgot_password($email, $new_password);
    echo $reset_message; // Display the result (e.g., error message or success)
} else {
    // If not a POST request, show a form for the user to enter their email and a new password.
    ?>
