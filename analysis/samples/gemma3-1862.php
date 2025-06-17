

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// 1. Get the email from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // 2. Validate the email
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // 3.  Check if the email exists in the database
        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, email FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Email exists, generate a unique token and send a reset password email
            $token = bin2hex(random_bytes(32)); // Generate a random token
            $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token; // Replace with your website URL

            // Prepare the reset password email
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the link below to reset your password:
" . $reset_link;
            $headers = "From: your_email@example.com\r
";
            mail($to, $subject, $message, $headers);

            // Store the token in the database (for later retrieval) -  This is crucial!
            $conn->query("UPDATE users SET token = '$token' WHERE email = '$email'");

            $success = "Password reset email sent to $email.  Check your inbox.";
        } else {
            $error = "Email address not found.";
        }
    }
}
?>
