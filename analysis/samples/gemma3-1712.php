

<?php

// IMPORTANT:  This is a simplified example for demonstration purposes.
// In a real-world application, you should:
// 1. Store passwords securely (hashed, not plain text).
// 2. Use a robust session management system.
// 3. Implement proper error handling and validation.
// 4. Consider using a dedicated password reset library.

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Email setup (replace with your email server details)
$email_to = 'user@example.com'; // Recipient email
$email_from = 'noreply@example.com'; // Sender email
$email_subject = 'Password Reset';

// Function to send email
function sendEmail($to, $from, $subject, $message) {
    // Placeholder - replace with your actual email sending logic
    // This example simply prints the email content.

    // In a real application, use a library like PHPMailer or SwiftMailer
    // to handle email sending securely and reliably.
    echo "Sending email to: " . $to . "
";
    echo "Subject: " . $subject . "
";
    echo "Message: " . $message . "
";
}

// Function to handle password reset request
function forgotPassword($username) {
    // 1. Retrieve user data from database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT id, email FROM users WHERE username = '$username'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $userEmail = $user['email'];

        // 2. Generate a unique, time-based token
        $token = bin2hex(random_bytes(32));  // More secure token generation

        // 3. Store the token and user ID in the database
        $conn->query("UPDATE users SET reset_token = '$token' WHERE username = '$username'");

        // 4. Build the email content
        $message = "Click on the following link to reset your password: ";
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token"; // Replace with your URL
        $message .= "<br><a href='$resetLink'>" . $resetLink . "</a>";

        // 5. Send the email
        sendEmail($userEmail, $email_from, $email_subject, $message);

        // 6.  Success! (Optional: Redirect to a confirmation page)
        return true;
    } else {
        // User not found
        return false;
    }

    $conn->close();
}



// --- Example Usage (This part would be part of your form) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    if (!empty($username)) {
        if (forgotPassword($username)) {
            echo "Password reset link has been sent to your email.";
        } else {
            echo "User not found or error occurred during password reset.";
        }
    } else {
        echo "Please enter your username.";
    }
}
?>
