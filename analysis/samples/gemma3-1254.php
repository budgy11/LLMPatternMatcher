

<?php

// This script requires a database connection setup (e.g., PDO)
// For simplicity, we'll use a basic array to store user data.
// In a real application, you'd connect to your database.

//  Example user data (replace with your database connection and retrieval)
$users = [
    'john.doe@example.com' => 'password123',
    'jane.smith@example.com' => 'secure_password',
];

// Function to handle the forgot password request
function forgot_password($email)
{
    // 1. Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if the user exists
    if (!isset($users[$email])) {
        return "User not found.";
    }

    // 3. Generate a unique token (for security)
    $token = bin2hex(random_bytes(32)); // Use cryptographically secure random bytes

    // 4. Store the token and expiration date in the database (or in our example array)
    //    This is the key step for a real application
    //    We'll just store it in an array for demonstration
    $users[$email]['reset_token'] = $token;
    $users[$email]['reset_expiry'] = time() + 3600; // Token expires in 1 hour (3600 seconds)
    

    // 5.  Create a reset link
    $reset_link = "https://yourdomain.com/reset_password?token=" . urlencode($token);

    // 6. Send the reset link (in a real application, use email)
    //    For this example, just return the link
    return "Please click on the following link to reset your password: " . $reset_link;
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $reset_message = forgot_password($email);
    echo $reset_message;
}
?>
