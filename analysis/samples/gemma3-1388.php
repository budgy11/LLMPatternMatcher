

<?php

/**
 * Forgot Password Function
 *
 * This function generates a unique token, sends a password reset email to the user,
 * and optionally, sets a session variable for the reset link.
 *
 * @param string $email      The email address of the user.
 * @param string $baseUrl   The base URL of your application (e.g., 'http://example.com').
 * @param string $resetTokenPrefix  (Optional) A prefix to prepend to the generated token.  Good for security. Defaults to 'reset_'.
 * @return bool           True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl, string $resetTokenPrefix = 'reset_') {
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

    // 2. Store the Token (Database) - **IMPORTANT:  Securely store this**
    //   This is a simplified example -  in a production environment,
    //   you should use a secure database and proper sanitization.
    //   This demonstrates the concept.

    // Assuming you have a database connection called $db
    // $db->query("DELETE FROM password_resets WHERE email = '$email'"); // Clear previous resets
    // $db->query("INSERT INTO password_resets (email, token, created_at) VALUES ('$email', '$token', NOW())");


    // 3. Create the Reset Link
    $resetLink = $baseUrl . "/reset-password?token=" . $token;

    // 4. Send the Password Reset Email
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on the following link: " . $resetLink;
    $headers = "From: " .  "Your Application Name <noreply@" . $baseUrl . ">" . "\r
"; //Replace with your application's no-reply email address

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Handle email sending failure (e.g., log the error)
        error_log("Failed to send password reset email for $email");
        return false;
    }
}



// Example Usage (for demonstration - replace with your actual application logic)
// Assuming you have a function to display a form for forgot password
// $email = $_POST['email']; // Get email from form

// if (isset($email)) {
//     if (empty($email)) {
//         echo "Please enter your email address.";
//     } else {
//         if (forgotPassword($email, 'http://yourwebsite.com')) {
//             echo "Password reset email has been sent.  Check your inbox!";
//         } else {
//             echo "An error occurred while sending the email.";
//         }
//     }
// }

?>
