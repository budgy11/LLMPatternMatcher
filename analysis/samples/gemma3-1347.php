

<?php

// Ensure this file is part of a secure application
// and that your database connection is properly configured
// for security and access control.

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the email address from the form
    $email = $_POST["email"];

    // Validate the email (basic validation - improve this for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Prepare the SQL query to select the user
        $query = "SELECT id, password, email FROM users WHERE email = '$email'";

        // Execute the query
        $result = mysqli_query($GLOBALS["database"], $query);

        // Check if the query was successful
        if ($result) {
            // Get the result row
            $row = mysqli_fetch_assoc($result);

            // Check if a user with the email exists
            if ($row) {
                // Generate a unique token (using a more robust method in production)
                $token = bin2hex(random_bytes(32));

                // Prepare the update query
                $update_query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";

                // Execute the update query
                mysqli_query($GLOBALS["database"], $update_query);

                // Send an email to the user with the reset link
                $to = $email;
                $subject = "Password Reset Link";
                $message = "Click the following link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=$token'>" . $_SERVER["PHP_SELF"] . "?reset=$token</a>";
                $headers = "From: your_email@example.com"; // Replace with your email

                mail($to, $message, $headers);

                $success = "Password reset email sent to $email.";
            } else {
                $error = "No user found with this email address.";
            }
        } else {
            $error = "Error querying the database.";
        }
    }
}
?>
