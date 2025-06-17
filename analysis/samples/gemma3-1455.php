

<?php

// This is a basic example and should be adapted for your specific setup
//  - Database credentials should be stored securely (e.g., environment variables)
//  - This example is not production-ready and lacks proper input validation, security measures,
//    and logging.

// Database configuration (replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_database_user';
$dbPass = 'your_database_password';

// Email settings (replace with your email server details)
$emailFrom = 'your_email@example.com';
$emailTo = '%user_email%'; // Placeholder for user's email (will be replaced dynamically)
$emailSubject = 'Password Reset';

// Function to send the password reset email
function sendPasswordResetEmail($email, $resetToken) {
    //  - Replace this with your email sending logic (e.g., using PHPMailer)
    //  - Make sure you handle email sending errors properly.

    $message = "Please click on the following link to reset your password: <a href='" . base64_decode("{$resetToken}") . "'>Reset Password</a>";
    $headers = "From: " . $emailFrom . "\r
";
    $headers .= "Reply-To: " . $emailFrom . "\r
";

    mail($emailTo, $message, $headers);
}


// Function to handle the forgot password request
function forgotPassword($email) {
    // Validate email format (basic check - improve this for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    // 1. Generate a unique, secure token (e.g., using random_bytes)
    $resetToken = bin2hex(random_bytes(32)); // Use bin2hex for security

    // 2.  Generate a token entry in the database
    //    This is a simplified example.  You likely want to store expiration
    //    and potentially other fields.

    //  Important:  Never store passwords directly. Use a hash (e.g., bcrypt)
    //  Instead of storing 'password' in the database, you'd store the hashed password.

    //  This simplified example assumes a table called 'users' with columns 'email' and 'password'
    //  and we're just generating a token.  Adapt to your schema.

    //  For production, use prepared statements to prevent SQL injection.
    $sql = "INSERT INTO users (email, reset_token, created_at) VALUES (?, ?, NOW())"; // Create table if it doesn't exist
    $stmt = $conn->prepare($sql);  // Prepare statement

    if ($stmt->execute([$email, $resetToken])) {
      // 3.  Send the password reset email
      sendPasswordResetEmail($email, $resetToken);
      return 'Password reset email sent. Please check your inbox.';
    } else {
      // Handle database error
      return 'An error occurred while processing your request.';
    }
}



// Example usage (replace with your form handling code)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $resetResult = forgotPassword($email);
    echo $resetResult;
} else {
    // Display the forgot password form
    ?>
