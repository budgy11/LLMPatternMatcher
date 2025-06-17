

<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle the forgot password process
function forgotPassword($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if the user exists
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $sql = "SELECT id, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['password']; //Retrieve the hashed password.

        // 3. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generate a secure random token
        
        // 4. Update the user's record with the token
        $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$userId'";
        if ($conn->query($sql) === TRUE) {
            // 5. Send an email with the token and a link
            $emailTo = $email;
            $subject = "Password Reset Link";
            $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
            $headers = "From: your_email@example.com" . "\r
";

            if (mail($emailTo, $subject, $message, $headers)) {
                return "Password reset link sent to your email.  Please check your inbox.";
            } else {
                return "Failed to send password reset email.";
            }
        } else {
            return "Error updating user record.";
        }
    } else {
        return "User not found.";
    }

    $conn->close();
}


// Example usage (this is just for demonstration, don't use this directly in a form)
// $email = $_POST['email']; // Get email from form
// $resetMessage = forgotPassword($email);
// echo $resetMessage;

?>
