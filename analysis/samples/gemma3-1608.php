

<?php

// Ensure this file is not directly accessible via web browser
if (session_status() !== 'session_started') {
    die('This file should be included within a session.');
}

// Database connection (replace with your actual details)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to reset password
function forgot_password($email) {
    // 1. Check if email exists in the database
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT id, email FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $user_email = $result->fetch_assoc()['email'];

        // 2. Generate a unique token
        $token = bin2hex(random_bytes(32)); // More secure random token

        // 3.  Store token in database (temporary)
        $conn->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

        // 4.  Email the user with a link to reset password
        $to = $email;
        $subject = 'Password Reset Link';
        $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . '?reset_token=$token' . "'>Reset Password</a>";
        $headers = "From: your_email@example.com"; // Replace with your email

        mail($to, $message, $headers);

        $conn->close();
        return true;

    } else {
        $conn->close();
        return false;
    }
}


// Example Usage (This would typically be triggered from a form submission)
//  Assume a form has an email field and submits a request.

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgot_password($email)) {
        echo "<p>Password reset link has been sent to your email address.</p>";
    } else {
        echo "<p>An error occurred while processing your request.</p>";
    }
}

?>
