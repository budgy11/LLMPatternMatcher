

<?php

// Database connection (Replace with your actual database credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to generate a random password
function generateRandomPassword(int $length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-{}|;:\'",<.>?/';
    $password = '';
    $max_length = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $max_length - 1)];
    }
    return $password;
}


/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (Basic check, improve for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log the error - important for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);
    if (!$user) {
        error_log("User not found with email: " . $email); // Log user not found
        return false;
    }

    // 3. Generate a random password
    $password = generateRandomPassword();

    // 4. Hash the password (Important for security - use strong hashing)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 5. Update the user's password in the database
    $result = updateUserPassword($user['id'], $hashedPassword);
    if (!$result) {
        error_log("Failed to update password for user: " . $email);
        return false;
    }

    // 6. Send the password reset email
    if (!sendResetPasswordEmail($user['email'], $password)) {
        // Handle email sending failure - logging is critical here.
        error_log("Failed to send password reset email for user: " . $email);
        // You might want to retry sending the email or take other actions.
        // Consider setting a flag in the database to indicate an error.
        return false;
    }


    return true;
}


/**
 * Helper functions (Replace with your actual database interactions)
 */

function getUserByEmail(string $email)
{
    // Replace this with your database query to fetch the user by email
    // This is just a placeholder.  Adapt to your database schema.
    // Example:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($conn, $query); // Or use PDO, etc.
    // $user = mysqli_fetch_assoc($result);
    // return $user;

    // Dummy user for testing
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'name' => 'Test User'],
        ['id' => 2, 'email' => 'another@example.com', 'name' => 'Another User']
    ];

    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }

    return null; // User not found
}


function updateUserPassword(int $userId, string $hashedPassword) {
    // Replace this with your database query to update the password
    // Example:
    // $query = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";
    // $result = mysqli_query($conn, $query);

    // Dummy update for testing
    // In a real application, you'd actually update the database.
    //  You'll likely use PDO or a similar database abstraction layer.
    // For demonstration, let's just simulate a success.
    return true;
}



function sendResetPasswordEmail(string $email, string $password)
{
    // Replace this with your email sending logic
    // Example:
    // $to = $email;
    // $subject = 'Password Reset';
    // $message = 'To reset your password, click on this link: ' . '<a href="reset_link">';
    // $headers = 'From: your_email@example.com' . "\r
";
    // $sent = mail($to, $subject, $message, $headers);

    // Simulate email sending for testing.
    // In a real application, you'd use a library or service to send emails.
    //  You'd also need to generate a unique reset token to secure the link.

    // Simulate success for testing
    return true;
}



// Example usage (Demonstration)
$email = 'test@example.com';
if (forgotPassword($email)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to reset password for " . $email . "
";
}

?>
