

<?php

// Ensure this is a secure file and not accessible directly via web browser.
// It's best practice to store credentials in a database, not directly in the file.
// This example is for demonstration purposes ONLY.  DO NOT use this in production without proper security measures.

// Database configuration (replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Function to reset password
function forgot_password($email, $new_password, $db_host, $db_name, $db_user, $db_pass) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // 3. Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 4. Hash the new password (IMPORTANT for security)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 5. Update the password in the database
    $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    if ($conn->query($query) === TRUE) {
        return "Password reset email sent. Please check your email.";
    } else {
        return "Error resetting password. " . $conn->error;
    }

    // 6. Close the connection
    $conn->close();
}


// Example usage (FOR DEMO ONLY - DON'T RUN THIS IN PRODUCTION without proper security)
// This is just an example. It's dangerous to expose this in a real application.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];

    $result = forgot_password($email, $new_password, $db_host, $db_name, $db_user, $db_pass);
    echo $result;
} else {
    // Display a form to request password reset
    ?>
