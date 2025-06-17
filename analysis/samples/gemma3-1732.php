

<?php

// Assuming you have a database connection established (e.g., $db)
// and you have a 'users' table with a 'email' column.

function forgotPassword($email, $db) {
    // Validate email format (optional, but recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // Check if the email exists in the database
    $result = $db->query("SELECT id, password, salt FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['password'];
        $salt = $user['salt'];

        // Generate a unique token (for security)
        $token = bin2hex(random_bytes(32));

        // Hash the token with the salt
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        // Store the token in the database (associate it with the user's email)
        $db->query("UPDATE users SET password_reset_token = '$hashedToken' WHERE email = '$email'");

        // Send an email with a link to reset the password
        $subject = "Password Reset Link";
        $to = $email;
        $message = "Click on this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";
        $headers = "From: your_email@example.com" . "\r
";

        if(mail($to, $subject, $message, $headers)) {
          return "Password reset email sent. Please check your inbox.";
        } else {
          return "Password reset email could not be sent.  Check your mail server configuration.";
        }

    } else {
        return "User not found.";
    }
}

// Example Usage (in your form/script):
// $email = $_POST['email'];  // Get email from form
// $resetMessage = forgotPassword($email, $db);
// echo $resetMessage;

?>
