?>


<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgotPassword($email, $newPassword) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 3. Hash the new password (Important for security!)
    $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

    // 4. Prepare and execute the SQL query
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";  // Assuming 'users' is your table name and 'email' is the column
    if ($conn->query($sql) === TRUE) {
        return "Password reset successfully.  Check your email for details.";
    } else {
        return "Error resetting password.  " . $conn->error;
    }

    // 5. Close the connection
    $conn->close();
}

// Example usage (Simulated form submission - for demonstration only)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    $resetMessage = forgotPassword($email, $newPassword);
    echo $resetMessage; // Output the message (you'd typically display it on a page)
}

?>
