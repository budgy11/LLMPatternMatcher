

<?php

// Ensure this file is protected (e.g., .htaccess or server configuration)
// to prevent direct access to the password reset file.

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Email configuration (replace with your email settings)
$from_email = 'your_email@example.com';
$to_email = '%user_email%'; // Use placeholder for user's email
$subject = 'Password Reset Request';
$headers = "From: $from_email\r
";
$headers .= "Reply-To: $from_email\r
";

// Generate a unique, secure token
function generate_token() {
    return bin2hex(random_bytes(32));
}

// Check if the request is valid
if (isset($_POST['email']) && isset($_POST['token'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email input
    $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING); // Sanitize token input

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the token exists in the database (important security step)
    $result = mysqli_query($GLOBALS['database'], "SELECT id, email FROM users WHERE email = '$email' AND token = '$token'");

    if (mysqli_num_rows($result) > 0) {
        // Token exists, proceed with password reset
        $user_id = mysqli_fetch_assoc($result)['id'];
        $user_email = mysqli_fetch_assoc($result)['email'];

        //  Create a temporary password (strong password)
        $temp_password = 'P@$$wOrd'; // Example -  Use a stronger password generation method in a real app.
        //  Generate a unique token for the reset process
        $reset_token = generate_token();

        // Update the user's record with the temporary password and the new reset token
        mysqli_query($GLOBALS['database'], "UPDATE users SET password = '$temp_password', token = '$reset_token', password_reset_expiry = NOW() WHERE id = '$user_id'");

        // Send the password reset email
        $message = "Please use the following link to reset your password:
" .
                   '<a href="' . $_SERVER['REQUEST_URI'] . '?token=' . $reset_token . '">Reset Password</a>';  // Use the same URL for the reset link
        mail($to_email, $subject, $message, $headers);

        echo "Password reset link sent to your email.  Check your inbox.";

    } else {
        echo "Invalid token or user not found.";
    }
} else {
    echo "Invalid request.";
}

// Example usage (for testing - usually handled through a form)
//  This is just an example -  integrate this with a form to collect the email.
//  Implement proper error handling and security best practices.
?>
