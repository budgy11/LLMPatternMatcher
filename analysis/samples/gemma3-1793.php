
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <form method="post" action="">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php

// Assuming you have a database connection established, let's call it $db

// Function to handle the forgot password flow
function forgotPassword($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the email exists in the users table
    $stmt = $db->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User not found.";
    }

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random token

    // 4.  Update user's record with the token
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->execute([$token, $email]);

    // 5.  Send an email with the reset link
    $subject = "Password Reset Link";
    $to = $email;
    $from = "your_email@example.com"; // Replace with your actual email address
    $resetLink = "http://yourwebsite.com/reset_password?token=$token"; // Replace with your website URL

    $headers = "From: " . $from . "\r
";
    $headers .= "Reply-To: " . $from . "\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";

    $message = "<html><body>";
    $message .= "<h1>Password Reset</h1>";
    $message .= "<p>Click on the link below to reset your password:</p>";
    $message .= "<a href='" . $resetLink . "'>Reset Password</a>";
    $message .= "<p>This link will expire in 24 hours.</p>";
    $message .= "</body></html>";

    if (mail($to, $subject, $message, $headers)) {
        return "Password reset email sent to $email. Please check your inbox.";
    } else {
        return "Failed to send password reset email.";
    }
}

// Example Usage (Assuming you have a form to submit the email)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $result = forgotPassword($email);
    echo "<p>" . $result . "</p>";
}

?>
