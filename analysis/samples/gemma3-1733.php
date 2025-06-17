

<?php

// This script demonstrates a basic forgot password functionality.
// **IMPORTANT:  This is a simplified example and should be adapted for a production environment.**
//  - Security considerations are crucial and need thorough implementation.
//  -  Use a real email sending service instead of `mail()` for reliability and deliverability.
//  -  Implement rate limiting to prevent abuse.
//  -  Store passwords securely (hashed and salted) - NEVER store passwords in plain text.

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

//  Generate a unique token for the password reset request
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generate a 32-byte random string
}

// Function to reset password
function resetPassword($email, $token) {
    // 1. Validate the token (ensure it's valid and hasn't expired)
    //    - Check if the token exists in the database for the given email
    //    - Implement an expiration time for the token (e.g., 30 minutes)

    // Example (Simplified Token Validation) - Replace with proper validation
    $query = "SELECT id FROM users WHERE email = '$email' AND token = '$token'";
    $result = mysqli_query($GLOBALS['conn'], $query);

    if (mysqli_num_rows($result) > 0) {
        // Token is valid - proceed to generate and email the reset link
        return true;  // Indicate success
    } else {
        return false;  // Token is invalid
    }
}

// Function to send the reset password email
function sendResetPasswordEmail($email, $resetLink) {
    // Replace with your email sending service/library
    // This is a placeholder using `mail()` - NOT recommended for production
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . $resetLink;
    $headers = 'From: your_email@example.com' . "\r
"; // Replace with your email address

    if (mail($to, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}



// Handle the forgot password request (Example - This is just the route part)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // 1. Generate a unique token
        $token = generateUniqueToken();

        // 2. Store the token in the database, associated with the email
        $query = "INSERT INTO password_resets (email, token, created_at) VALUES ('$email', '$token', NOW())";
        mysqli_query($GLOBALS['conn'], $query);

        // 3.  Generate the reset password link
        $resetLink = '?token=' . $token; //  Adjust URL structure as needed.  Consider using a session-based token.

        // 4. Send the email
        $success = sendResetPasswordEmail($email, $resetLink);

        if ($success) {
            $message = "Password reset email sent to $email.";
        } else {
            $error = "Failed to send password reset email.";
        }
    }
}

//Database Connection (Important - Only for this example)
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
