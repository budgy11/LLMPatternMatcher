

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to verify the password
 * reset request. It sends an email to the user with a link to reset their
 * password.
 *
 * @param string $email The email address of the user.
 * @return bool True if the password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a secure, random token

    // 3. Store Token (e.g., in database - this is a simplified example)
    //   Replace this with your actual database interaction.
    //   This is just a placeholder.
    $db_token = $token; //  This would be stored in the database, keyed by email
    // Example:
    // $result = $this->db->insert('password_resets', ['email' => $email, 'token' => $token, 'created_at' => time()]);
    // if (!$result) {
    //   return false;
    // }
    
    // 4. Create the Password Reset Link
    $resetLink = "http://yourdomain.com/reset-password?token=$token"; // Replace with your actual domain

    // 5. Send the Email
    //  Replace with your actual email sending logic.
    //  This is a simplified example.  Use a proper email library.
    
    $subject = "Password Reset Request";
    $message = "Click this link to reset your password: " . $resetLink;
    $headers = "From: your_email@example.com\r
"; // Use a valid sender address
    
    // Send the email (using a library like PHPMailer is recommended)
    // $sent = mail("user_email@example.com", $subject, $message, $headers);

    // Simple echo for demonstration (replace with actual sending)
    echo "Email sent to: " . $email . "
";
    echo "Reset link: " . $resetLink . "
";


    // 6. Return True (indicating success)
    return true;
}


// Example Usage:
//  Uncomment these lines to test:
// $email = "test@example.com";  // Replace with a valid email
// if (forgotPassword($email)) {
//     echo "Password reset email sent successfully.
";
// } else {
//     echo "Failed to send password reset email.
";
// }

?>
