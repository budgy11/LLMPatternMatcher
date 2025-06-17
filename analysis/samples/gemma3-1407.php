

<?php

// Assuming you have a database connection established (e.g., $db)
// and that you have a 'users' table with a 'email' and 'password' column.

function forgotPassword($email, $db) {
    // 1. Validate email
    if (empty($email)) {
        return "Please enter an email address.";
    }

    // 2. Check if the user exists
    $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];

        // 3. Generate a unique token for password reset
        $resetToken = bin2hex(random_bytes(32));

        // 4.  Store the token in the database (associated with the user)
        $db->query("UPDATE users SET reset_token = '$resetToken' WHERE id = $userId");

        // 5.  Send an email with the reset link
        $subject = "Password Reset Link";
        $to = $email;
        $from = 'your_email@example.com'; // Replace with your email address
        $message = "Please click on the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?email=' . urlencode($email) . '&token=' . urlencode($resetToken) . '" >Reset Password</a>';
        $headers = "From: " . $from . "\r
";
        $headers .= "Reply-To: " . $from . "\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();

        if ($db->query($headers)) { //validate headers
            $db->query("INSERT INTO password_resets (user_id, token, expires_at) VALUES ($userId, '$resetToken', NOW() + INTERVAL 24 HOUR)"); //store the token in password_resets table
            return "Password reset email has been sent.  Please check your inbox.";
        } else {
            return "Failed to send password reset email.  Check your email settings.";
        }


    } else {
        return "User not found.";
    }
}

// Example usage:
// $email = 'testuser@example.com';
// $result = forgotPassword($email);
// echo $result;

?>
