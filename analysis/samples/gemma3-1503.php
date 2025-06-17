

<?php

// This code assumes you have a database connection established.
// For demonstration, we'll use a simple in-memory array.
// In a real application, you would use a database connection.

// Example user data (replace with your actual database retrieval)
$users = [
    'john.doe@example.com' => 'password123',
    'jane.smith@example.com' => 'securePass',
];

/**
 * Forgets a user's password by sending a reset link to their email.
 *
 * @param string $email The email address of the user.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Check if the user exists
    if (!isset($users[$email])) {
        return false;
    }

    // Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32)); // Use cryptographically secure random bytes

    // Store the reset token and expiration time in a temporary table or session.
    // This example uses a simple in-memory storage for demonstration only.
    // In a real application, use a database or a more robust session management system.
    //  Example:
    //  $resetTokens[$email] = ['token' => $resetToken, 'expiresAt' => time() + 3600]; // Expires in 1 hour

    // For demonstration purposes, we just return the token.  You'd typically send an email.
    // In a real application, you'd send an email containing the token and a link to reset the password.
    return $resetToken;
}

// Example usage:
$email = 'john.doe@example.com';
$resetToken = forgotPassword($email);

if ($resetToken) {
    echo "Reset link generated for $email:  https://example.com/reset-password?token=$resetToken
"; // Replace with your actual URL.
    // In a real application, send an email with this link.
} else {
    echo "Failed to generate reset link for $email.
";
}


//  -----  Important Considerations and Enhancements  -----

// 1. Security:
//    - **Never** store passwords in plain text.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.
//    - Use a cryptographically secure random number generator (e.g., `random_bytes()`) to generate reset tokens.
//    -  Validate and sanitize all user input.  Prevent XSS and other vulnerabilities.
//    - Consider using a token expiration time.
//    -  Store reset tokens securely (database is generally preferred).

// 2. Database Integration:
//    - **Replace** the `$users` array with a database query to retrieve user information.
//    - Store reset tokens in a database table, along with the user ID and expiration time.
//    - Use parameterized queries to prevent SQL injection.

// 3. Email Sending:
//    -  Use a reliable email sending service (e.g., SendGrid, Mailgun, AWS SES) to send the reset password email.  Do not rely on direct SMTP connections for security reasons.
//    -  Include the reset token, a link to the reset password page, and instructions in the email.

// 4. Session Management:
//   - If using sessions, store the token and expiration time in the session.  Be mindful of session security.

// 5.  User Interface:
//   - Create a reset password page where the user can enter their email address.
//   -  Implement form validation on the reset password page.

?>
