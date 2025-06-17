

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = trim($_POST["email"]);

    // Validate the email format (basic validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } else {
        // Attempt to reset password
        if (reset_password($email)) {
            $success_message = "Password reset email sent to $email";
        } else {
            $error_message = "Could not reset password. Please try again.";
        }
    }
}
?>
