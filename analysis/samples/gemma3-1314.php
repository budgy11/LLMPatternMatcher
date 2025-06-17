

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique token and sends an email with a link
 * to reset the user's password.
 *
 * @param string $email The email address of the user who forgot their password.
 * @param string $reset_password_url The URL where the reset password form is located.
 * @param string $from_email The email address to send the password reset email from.
 * @param string $from_name The name to display in the email from.
 * @return bool True if email sent successfully, false otherwise.
 */
function forgot_password(string $email, string $reset_password_url, string $from_email, string $from_name): bool
{
    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 2. Hash the token (important for security!)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 3. Store the token in the database (replace with your actual database logic)
    //    This example uses a simple array for demonstration purposes.
    //    DO NOT use this in production without proper database integration.
    $users = [];  // Replace this with your user data retrieval logic
    if (isset($users[$email])) {
        $users[$email]['token'] = $hashed_token;
    } else {
        $users[$email] = [
            'token' => $hashed_token,
            'reset_url' => $reset_password_url
        ];
    }


    // 4. Construct the reset password URL
    $reset_url = $reset_password_url . '?token=' . urlencode($token);

    // 5. Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password: " . $reset_url;
    $headers = "From: " . $from_name . " <" . $from_email . ">\r
";
    $headers .= "Reply-To: " . $from_email . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Example Usage (FOR TESTING ONLY - DO NOT USE IN PRODUCTION)
// This example assumes you have a simple 'users' array for demonstration.
// In a real application, you'd use a database.

// Sample data (replace with your actual user data)
$users = [
    'test@example.com' => true
];

// Simulate a forgot password request
$email = 'test@example.com';
$reset_password_url = 'http://localhost/reset_password.php';
$from_email = 'your_email@example.com';
$from_name = 'Your Name';

if (forgot_password($email, $reset_password_url, $from_email, $from_name)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email to " . $email . "
";
}


// You will need to implement the 'reset_password.php' file to handle the form and password reset logic.
// This function only generates the email to send to the user.
?>
