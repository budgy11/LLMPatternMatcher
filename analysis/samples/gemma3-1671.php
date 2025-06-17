

<?php

// Assuming you have a database connection established (e.g., $db)
// This is just an example - adjust your database connection details.

function forgot_password($email, $db) {
    // 1. Verify Email Existence
    $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['email']; // In a real application, this would be a securely hashed password

        // 2. Generate a Token
        $token = bin2hex(random_bytes(32)); // Generates a secure random token
        
        // 3. Store Token in Database
        $db->query("UPDATE users SET password_reset_token = '$token' WHERE id = '$userId'");

        // 4. Email the User with Reset Link
        $resetLink = "https://yourdomain.com/reset_password.php?token=$token"; // Replace with your domain

        $to = $email;
        $subject = 'Password Reset';
        $message = "Click on the following link to reset your password: " . $resetLink;
        $headers = "From: your_email@example.com"; // Replace with your email address
        
        mail($to, $message, $headers); 

        return true; // Indicate success
    } else {
        return false; // Indicate email not found
    }
}

// Example Usage (for demonstration - modify to fit your application's flow)

// Assume you've set up a database connection:
// $db = new mysqli("localhost", "username", "password", "database_name");

// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }
//
// $email = "user@example.com"; // Replace with the user's email
//
// if (forgot_password($email, $db)) {
//     echo "Password reset email sent to $email";
// } else {
//     echo "Email not found or password reset link not generated.";
// }

// Close the database connection
// $db->close();

?>
