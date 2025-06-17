

<?php

// This is a simplified example and should be adapted for production environments.
// Never directly embed passwords in your code or database.  Use secure hashing and salting.

// 1. Database connection (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// 2. Get user email from the form
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // **Important: Validation and Sanitization**
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize the email
    if (empty($email)) {
        $message = "Please enter your email address.";
    } else {
        // 3. Check if the user exists in the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $sql = "SELECT id, email FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User exists, generate a token and send an email
            $token = bin2hex(random_bytes(32)); // Generate a random token

            // Store the token in the database for the user
            $sql_insert = "UPDATE users SET password_token = '$token' WHERE email = '$email'";
            if ($conn->query($sql_insert)) {
                // Email sending (replace with your actual email sending logic)
                $to = $email;
                $subject = 'Password Reset';
                $message = "Click on the following link to reset your password: <a href='reset_password.php?token=$token'>Reset Password</a>";
                $headers = "From: your_email@example.com\r
"; // Replace with your email address

                mail($to, $message, $headers);

                $message = "Password reset email sent to $email.";
            } else {
                $message = "Error updating user with token.";
            }


        } else {
            $message = "User not found.";
        }

    }
}

// 4. Display the message
?>
