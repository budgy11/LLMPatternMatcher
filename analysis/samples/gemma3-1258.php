

<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to reset password
function forgotPassword($email, $db) {
    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32));  //  Secure token generation

    // 2. Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 3. Store the token and user ID in the database
    $query = "INSERT INTO password_resets (user_email, token, hashed_token, created_at)
              VALUES ('$email', '$token', '$hashed_token', NOW())";

    if ($db->query($query) === TRUE) {
        // Email the user with a link to reset their password
        $reset_link = "http://yourdomain.com/reset_password?token=$token&user_id=$email"; // Customizable URL

        // Send the email (replace with your email sending logic)
        $to = $email;
        $subject = 'Password Reset';
        $message = "Click on the following link to reset your password: " . $reset_link;
        $headers = "From: your_email@example.com" . "\r
";

        mail($to, $subject, $message, $headers);

        return true; // Password reset request sent successfully
    } else {
        // Error inserting into the database
        return false;
    }
}

// Example Usage (This is just an example.  Don't directly execute this in a web environment)
// Assuming $db is your database connection object

// Example:  Reset password for user with email 'test@example.com'
// $success = forgotPassword('test@example.com', $db);

// if ($success) {
//     echo "Password reset request sent to test@example.com";
// } else {
//     echo "Error sending password reset request.";
// }

// *******************************************************************************
//  Database Table (Example -  Create this in your database)
// *******************************************************************************
// CREATE TABLE password_resets (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     user_email VARCHAR(255) NOT NULL,
//     token VARCHAR(255) NOT NULL UNIQUE,
//     hashed_token VARCHAR(255) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );


?>
