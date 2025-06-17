

<?php

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_db_user';
$dbPassword = 'your_db_password';

// Function to handle forgot password request
function forgotPassword($email)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Creates a secure random string

    // 3.  Store token and expiration in database.  Important for security!
    try {
        // Prepare the SQL query
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL Query to insert the token and email into the database
        $sql = "INSERT INTO forgot_password_tokens (email, token, expiry_timestamp)
                 VALUES (:email, :token, :expiry_timestamp)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry_timestamp', time() + 3600);  // Token expires in 1 hour (adjust as needed)
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle database errors
        return "Error generating token. Please try again.";
    }


    // 4.  Send Email (Replace with your email sending logic)
    $subject = "Password Reset Request";
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_URI'] . "?token=" . $token; // Construct the reset link
    $headers = "From: your_email@example.com"; // Replace with your email address

    // This is a placeholder.  You'll need to implement your email sending code here.
    // Example (using a simple email function - replace with a proper email library)
    // sendEmail($email, $subject, $message, $headers);

    return "Password reset link sent to your email. Please check your inbox.";
}

// Example Usage (for testing - remove this section in a real application)
// You'd typically receive this email address from a form submission.
$emailAddress = "test@example.com"; //  Replace with an actual email address to test
$result = forgotPassword($emailAddress);
echo $result;


// ----------------------------------------------------------------------------
//  Database Table (forgot_password_tokens) - Create this table in your database
// ----------------------------------------------------------------------------
// CREATE TABLE forgot_password_tokens (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     email VARCHAR(255) NOT NULL,
//     token VARCHAR(255) NOT NULL,
//     expiry_timestamp INT UNSIGNED NOT NULL  -- Timestamp when token expires
// );
//
//  You'll also likely want to add an index on the email column for faster lookups
//  CREATE INDEX idx_email ON forgot_password_tokens (email);

?>
