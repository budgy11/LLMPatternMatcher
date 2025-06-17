
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db
// and a table named 'users' with an 'email' column.

function forgotPassword($email, $db) {
    // 1. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // Email not found
    }

    // 2. Generate a unique token and store it in the database
    $token = bin2hex(random_bytes(32)); // Generates a secure random token
    $stmt = $db->prepare("UPDATE users SET password_token = ?, password_token_expiry = datetime('now + 1 hour') WHERE email = ?"); // Use datetime() for expiry
    $stmt->execute([$token, $email]);

    // 3. Send an email with a reset link
    $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
    sendResetPasswordEmail($email, $resetLink); // Implement this function (see example below)

    return true; // Password reset process initiated successfully
}

// Example Implementation of sendResetPasswordEmail function:
function sendResetPasswordEmail($email, $resetLink) {
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click the following link to reset your password: " . $resetLink;
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);
}

// Example Usage (within your route/controller):
// $email = $_POST['email'];
// if (isset($email)) {
//     if (forgotPassword($email, $db)) {
//         echo "Password reset link sent to your email.  Please check your inbox.";
//     } else {
//         echo "Invalid email or password reset link already exists.";
//     }
// }
?>
