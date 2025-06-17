

<?php

// This file requires a database connection.  Replace with your actual database credentials.
// This is a simplified example for demonstration purposes.  In a real application,
// you should implement more robust security measures.

// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS!
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

//  Email configuration
$from_email = 'your_email@example.com';  // Sender email address
$email_charset = 'UTF-8';

// Function to send an email
function send_email($to, $subject, $message, $from_email, $email_charset) {
    // Simple example - replace with a proper email library/service
    $headers = "From: " . $from_email . "\r
";
    $headers .= "Reply-To: " . $from_email . "\r
";
    $headers .= "Content-Type: text/html; charset=$email_charset\r
";

    mail($to, $subject, $message, $headers);
}


// Forgot Password Function
function forgot_password($username, $db) {
    // 1. Validate input (Basic - improve for production)
    if (empty($username)) {
        return 'Invalid username.';
    }

    // 2. Check if the user exists
    $result = $db->query("SELECT id, email FROM users WHERE username = '$username'");

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $user_id = $user_data['id'];
        $email = $user_data['email'];

        // 3. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Use strong random bytes

        // 4. Hash the token (Important for security)
        $hashed_token = password_hash($token, PASSWORD_DEFAULT);

        // 5. Update the user's record with the token
        if ($db->query("UPDATE users SET reset_token = '$hashed_token', reset_token_expiry = NOW() WHERE username = '$username'") !== false) {

            // 6. Send an email with a reset link
            $subject = 'Password Reset';
            $message = 'Click on the following link to reset your password: <a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '">' . urlencode('Reset Password') . '</a>';  // Build the link
            send_email($email, $subject, $message, $from_email, $email_charset);

            return 'Password reset link has been sent to your email.';
        } else {
            return 'Error updating password reset token.';
        }
    } else {
        return 'User not found.';
    }
}


// Example usage (This part would normally be in a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    // This is just an example.  In a real application, you'd have your database connection here.
    //  This example uses a placeholder database connection (replace with your actual connection)
    $db = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($db->connect_error) {
        die('Connection failed: ' . $db->connect_error);
    }

    $reset_message = forgot_password($username, $db);
    echo $reset_message; // Display the result
    $db->close();

}
?>
