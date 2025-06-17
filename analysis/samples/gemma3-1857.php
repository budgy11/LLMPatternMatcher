

<?php

// Assuming you have a database connection established and named $db

// Function to reset a user's password
function forgot_password($email, $new_password, $db) {
    // 1. Check if the email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the user's password in the database
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$hashed_password, $user['id']]);

    if ($stmt->rowCount() === 0) {
        // Update failed
        return false;
    }

    // 4. (Optional) Send an email notification (recommended)
    // This would involve sending an email to the user with a link to reset their password.
    // See the commented-out example below.

    return true;
}


// Example Usage (Illustrative - Replace with your actual email and password)
//
// $email = "testuser@example.com";
// $new_password = "new_secure_password";
//
// if (forgot_password($email, $new_password, $db)) {
//     echo "Password reset successful!  Check your email for instructions.";
// } else {
//     echo "Password reset failed.  Please try again.";
// }

// Example of sending an email (Requires configuration - SMTP/Email Setup)
/*
// Send email notification
function send_password_reset_email($email, $reset_link) {
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click on the following link to reset your password: " . $reset_link;
  $headers = "From: your_email@example.com" . "\r
" .
            "Reply-To: your_email@example.com";

  mail($to, $message, $headers);
}

// Example of generating the reset link (In a real application, you would use a token-based approach for security)
// $reset_link = "https://yourwebsite.com/reset_password?token=" . md5($email . time());
// send_password_reset_email($email, $reset_link);
*/

?>
