

<?php

// IMPORTANT: This is a simplified example for demonstration purposes.
// In a real application, you MUST implement robust security measures
// like hashing passwords, generating unique tokens, and validating them
// against a database.  This example is deliberately basic for clarity.

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_db_name';

// Check if the request is valid (GET request)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die("Invalid request method.");
}

// Get the email address from the request parameters
$email = $_GET['email'];

// Validate the email address (basic check - improve for production)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<p>Invalid email address.</p>";
    exit;
}

// 1.  Generate a unique token (using a library is highly recommended for security)
// For simplicity, we'll just use a timestamp, but this is VERY insecure.
$token = uniqid(); // Generate a unique ID

// 2.  Store the token and the email in the database (or a temporary table)
// This is where you would typically set the expiration time for the token.
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO forgot_password_tokens (email, token, expiry) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, time() + 3600]); // Expires in 1 hour (adjust as needed)
    
} catch (PDOException $e) {
    echo "<p>Error connecting to the database: " . $e->getMessage() . "</p>";
    exit;
}


// 3.  Send an email with a link containing the token
//  - Replace 'your_email@example.com' with your email address
//  - Use a proper email sending library (e.g., PHPMailer) for robust email handling
$to = 'your_email@example.com';
$subject = 'Password Reset';
$message = "Click on the following link to reset your password: " . "<a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?token=$token'>" . htmlspecialchars($_SERVER['PHP_SELF']) . "?token=$token</a>";
$headers = "From: your_website_name <your_website_email>"; // Replace with your website details

if (mail($to, $subject, $message, $headers)) {
    echo "<p>Password reset email has been sent to $email.</p>";
} else {
    echo "<p>Failed to send password reset email.</p>";
}

?>
