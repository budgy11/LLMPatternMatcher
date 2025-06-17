

<?php

/**
 * Forgets a user's password.
 *
 * This function sends a password reset link to the user's email address.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your application (e.g., 'http://example.com').
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log the error (important for debugging)
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number

    // 3. Store Token & User ID in Database (Simplified - in a real application, this is more secure)
    //    - You should *never* store passwords directly in the database.
    //    - Instead, store a hashed version of the password.
    //    - This simplified example just uses an array for demonstration.
    //    - In a real application, you would use a database query.

    //  Example:
    //  $user = get_user_by_email($email); // Assuming you have this function
    //  if ($user) {
    //      $user_id = $user['id'];
    //      save_token_for_user($user_id, $token);
    //  } else {
    //      return false; // User not found
    //  }

    // Simplified Example - using an array directly:
    $user_data = [
        'email' => $email,
        'token' => $token
    ];

    // 4. Create Password Reset Link
    $resetLink = $baseUrl . "/reset-password?token=" . urlencode($token);

    // 5. Send Password Reset Email
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on the following link: " . $resetLink;

    // You'll need to configure your email sending mechanism here.
    // This is just a placeholder - you'll need to use a real email library or service.
    // Example using a placeholder function:
    if (sendEmail($to, $subject, $message)) {
        return true;
    } else {
        error_log("Failed to send email for reset: " . $email);  // Log the error
        return false;
    }
}


/**
 * Placeholder function to simulate sending an email.
 * Replace this with your actual email sending logic.
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendEmail(string $to, string $subject, string $message): bool
{
    //  This is just a placeholder.  Replace this with your email sending logic.
    //  For example, you could use PHPMailer or SwiftMailer.
    //  Or, you could use a service like SendGrid or Mailgun.

    // Log the email details for debugging
    error_log("Simulated sending email to: " . $to . " Subject: " . $subject . " Message: " . $message);

    // Simulate success (replace with actual email sending)
    return true;
}


// Example Usage (for testing)
// $email = 'test@example.com'; // Replace with a valid email address
// if (forgotPassword($email, 'http://localhost/my-app')) {
//     echo "Password reset email sent to " . $email . "
";
// } else {
//     echo "Failed to send password reset email.
";
// }


?>
