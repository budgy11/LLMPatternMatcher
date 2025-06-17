

<?php

// Ensure this file is part of a secure application
// and handle credentials securely in a real-world scenario.
// This example is for demonstration purposes only.

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to handle the forgot password process
function forgot_password($email)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

    // 3.  Store the token in the database for the given email
    //  Important:  Use prepared statements to prevent SQL injection.
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

        $stmt = $pdo->prepare("INSERT INTO forgot_passwords (email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);
    } catch (PDOException $e) {
        // Handle database errors appropriately - logging is important!
        error_log("Database error: " . $e->getMessage());
        return "An error occurred while generating the token.  Please try again.";
    }

    // 4.  Send an email with the reset link
    $subject = "Password Reset Link";
    $to = $email;
    $from = 'your_app_email@example.com'; // Replace with your app's email address
    $reset_link = "http://yourwebsite.com/reset_password?token=$token"; // Replace with your website URL

    $headers = [
        'From' => $from,
        'Reply-To' => $from,
        'X-Mailer' => 'PHPmailer 5.2.0'
    ];

    // Example using PHPMailer (you'll need to install it: composer require phpmailer/phpmailer)
    //  You'll need to configure the SMTP settings (host, port, username, password) 
    //  for your email server.
    //  If you don't use PHPMailer, you'll need a different email sending library or service.
    //  For simplicity, this example doesn't include error handling for the email sending part.

    if (mail($to, $subject, $reset_link, $headers)) {
        return "Password reset instructions have been sent to your email address.";
    } else {
        //Handle email sending errors
        error_log("Failed to send email. Check your server's email configuration.");
        return "Password reset instructions could not be sent. Please check your email and try again.";
    }
}



// Example Usage (for testing - handle this safely in your application)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $result = forgot_password($email);
    echo "<p>" . $result . "</p>";
}
?>
