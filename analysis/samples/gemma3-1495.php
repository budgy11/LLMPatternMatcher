

<?php

// IMPORTANT:  This is a basic example and should be used with caution.
//  In a production environment, you MUST implement robust security measures 
//  to prevent abuse and unauthorized access.  This example is for educational purposes only.

// Database connection (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// 1.  Form Handling and Validation

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Basic email validation (improve this!)
    if (empty($email)) {
        $error_message = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address format.";
    } else {
        // 2.  Check for Existing User
        $query = "SELECT id, username FROM users WHERE email = '$email'";
        $result = mysqli_query($db_conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // User exists - generate and send reset token
            $reset_token = bin2hex(random_bytes(32)); // Generate a random token
            $query = "UPDATE users SET reset_token = '$reset_token', reset_token_expiry = NOW() WHERE email = '$email'";
            mysqli_query($db_conn, $query);

            // Email the reset link
            $to = $email;
            $subject = 'Password Reset';
            $message = "Please click on the following link to reset your password: " . '<a href="reset_password.php?token=' . $reset_token . '">Reset Password</a>';
            $headers = "From: your_email@example.com";  // Replace with your email
            mail($to, $message, $headers);

            // Display success message
            echo "<p>Password reset email has been sent to $email.  Please check your inbox.</p>";
        } else {
            // User does not exist
            echo "<p>No user found with that email address.</p>";
        }
    }
}


// 3.  Display the Form (if not submitted)
?>
