

<?php

// Database configuration (replace with your actual credentials)
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASSWORD', 'your_db_password');
define('DB_NAME', 'your_db_name');

// Function to reset password
function forgotPassword($email)
{
    // 1.  Database Connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2.  Generate a unique token
    $token = bin2hex(random_bytes(32));  // Using a strong, random token

    // 3.  Create a token record in the database
    $sql = "INSERT INTO forgot_tokens (email, token, expiry) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token, time() + 3600); // Expires in 1 hour
    $stmt->execute();

    // 4.  Confirmation message
    if ($stmt->affected_rows === 0) {
        return false; // Email or token already exists
    }

    // 5.  Close the database connection
    $stmt->close();
    $conn->close();

    return true;
}


// Example Usage (This is just for demonstration - don't include this in your actual code!)
// This will generate a token and attempt to insert it into the database.
//  You would typically trigger this when a user clicks a "Forgot Password" link.

// $userEmail = "test@example.com"; // Replace with the user's email

// if (forgotPassword($userEmail)) {
//     echo "Password reset instructions have been sent to $userEmail.";
// } else {
//     echo "An error occurred while generating the password reset link.";
// }
?>
