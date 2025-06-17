

<?php

// This is a simplified example and should be adapted for a production environment.
// It's crucial to implement security measures like:
// - Proper database escaping
// - Token generation using cryptographically secure methods
// - Rate limiting to prevent brute-force attacks
// - Email sending with secure transport (SSL/TLS)

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";


// Function to reset password
function forgotPassword($email, $db) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random bytes
    
    // 3.  Hash the token for security.  Avoid storing plain tokens.
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4.  Store the token in the database
    $query = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, NOW())";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email, $hashed_token);
    $stmt->execute();

    // 5.  Send an email with a reset link
    $resetLink = "http://yourdomain.com/reset-password?token=$token"; // Replace with your domain
    
    // You'll need to implement your email sending functionality here
    // Example (replace with your actual email sending function)
    // sendResetEmail($email, $resetLink);

    return "Password reset link sent to your email.  Please check your inbox.";
}

// Example Usage (for testing) -  **DO NOT USE THIS IN PRODUCTION**
// This section demonstrates how to call the function and handle the response.
// In a real application, this would be part of a web form handling the request.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Example database connection (simplified)
    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $result = forgotPassword($email, $db);
    echo $result; // Output the result to the user (e.g., display on the page)
    $db->close();
} else {
    // Display a form for the user to enter their email
    ?>
