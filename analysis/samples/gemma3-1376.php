

<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle password reset
function forgot_password($email) {
    // 1. Check if the email exists in the user database
    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $result = $stmt->execute();

    if ($result === false) {
        // Handle database error
        error_log("Database error: " . $db->error);
        return false;
    }

    $row = $stmt->fetch_assoc();

    if (!$row) {
        // User not found
        $stmt->close();
        return false;
    }

    // 2. Generate a unique, secure token (e.g., UUID)
    $token = uniqid(time(), true); // true for timestamping
    $token = md5($token);  //  Hash the token for security. You could also use a more robust hashing algorithm like bcrypt.

    // 3. Prepare the reset URL
    $reset_url = "http://yourwebsite.com/reset_password?token=" . urlencode($token);  // Replace with your website URL

    // 4. Prepare the reset email content
    $subject = "Password Reset Request";
    $message = "Dear User,

" .
               "You have requested to reset your password for your account.
" .
               "Please click on the following link to reset your password:
" .
               "<a href='" . $reset_url . "'>Reset Password</a>

" .
               "This link will expire in 24 hours.

" .
               "Sincerely,
" .
               "Your Website Team";


    // 5. Send the email
    $headers = "From: Your Website <noreply@yourwebsite.com>

";
    if (mail($email, $subject, $message, $headers)) {
        // Email sent successfully
        $stmt->close();
        return true;
    } else {
        // Email sending failed
        $stmt->close();
        error_log("Failed to send email to: " . $email);
        return false;
    }
}


// Example Usage (assuming $email is the user's email address)
// if (isset($_POST['reset_email'])) {
//     $email = $_POST['reset_email'];
//     if (forgot_password($email)) {
//         echo "Password reset email sent to " . $email . ". Please check your inbox.";
//     } else {
//         echo "Error sending password reset email. Please try again.";
//     }
// }
?>
