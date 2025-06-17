

<?php

// This is a simplified example and should be used with caution.
// In a production environment, you MUST:
// 1.  Store passwords securely (hashed, not plain text).
// 2.  Use a robust session management system.
// 3.  Implement proper rate limiting to prevent brute-force attacks.
// 4.  Validate user input thoroughly.
// 5.  Send emails securely (HTTPS) and handle email delivery failures.


// Example user data (replace with your database connection)
$users = [
    'john.doe@example.com' => 'hashed_password_john',
    'jane.smith@example.com' => 'hashed_password_jane',
];

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Verify the email exists
    if (!isset($users[$email])) {
        return false; // Email doesn't exist
    }

    // 2. Generate a unique token (e.g., using uuid_v4())
    $token = bin2hex(random_bytes(32)); // Stronger random token

    // 3.  Store the token in the database associated with the email.
    //     This example uses a simple array.  In a real application, you'd use a database.
    $users[$email] = $token;  // Update the array to store the token

    // 4.  Send the password reset email (This is just a placeholder)
    $subject = 'Password Reset Request';
    $message = "To reset your password, please click this link: " . $_SERVER['REQUEST_URI']; // Use the URL of the reset form
    $headers = ['Content-Type: text/html']; // Use HTML for the email

    //  In a real application, use a library like PHPMailer to send emails.
    //  For example:
    //  mail($email, $subject, $message, $headers);
    //  Make sure the email is sent over HTTPS.

    // 5. Return true to indicate success
    return true;
}

// Example usage (for testing - NEVER expose this directly to users)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (is_email($email)) {
            if (forgot_password($email)) {
                echo "<p>Password reset link sent to $email.</p>";
            } else {
                echo "<p>Error: Could not reset password. Please check your email or try again later.</p>";
            }
        } else {
            echo "<p>Invalid email address.</p>";
        }
    }
}

?>
