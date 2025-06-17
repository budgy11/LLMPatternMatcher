

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the reset password form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = $_POST["email"];

    // Validate the email format (Basic validation - improve as needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Hash the password (Important for security - using password_hash)
        $hashed_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

        // SQL Query -  **IMPORTANT:  Use prepared statements to prevent SQL injection!**
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql); // Assuming $conn is your database connection
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $success = "Password reset email sent to $email";
        } else {
            $error = "Error resetting password.  Check database connection or permissions.";
        }
        $stmt->close();
    }
}
?>
