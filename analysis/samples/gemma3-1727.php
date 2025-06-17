

<?php

// This is a simplified example and should be adapted for production environments.
// It's crucial to implement proper security measures like rate limiting,
// hashing passwords, and using secure communication channels (HTTPS).

// 1. Database Connection (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPassword = 'your_password';

// 2. Function to Reset Password
function forgotPassword($email, $newPassword) {
    // 1. Check if the email exists
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);  // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Email not found
    }

    // 2. Hash the new password (VERY IMPORTANT)
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 3. Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedNewPassword, $email);  // "ss" indicates two string parameters

    if ($stmt->execute()) {
        $stmt->close();
        return true; // Password reset successful
    } else {
        $error = $stmt->error;
        $stmt->close();
        return false; // Password reset failed
    }
}


// 3. Example Usage (FOR DEMONSTRATION ONLY - DON'T USE IN PRODUCTION)
//  This demonstrates how you would call the function.
//  In a real application, this would come from a form submission.

// Simulate a request (Replace with actual form data)
$emailToReset = "testuser@example.com";  // Replace with the email to reset
$newPassword = "NewSecurePassword123";  // Replace with the new password

// Call the function
if (forgotPassword($emailToReset, $newPassword)) {
    echo "Password reset successful.  Please check your email.";
} else {
    echo "Password reset failed.  Please try again or contact support.";
}

// 4.  Important Security Considerations and Production Best Practices:

// * **HTTPS:** ALWAYS use HTTPS to encrypt communication between the user's browser and your server,
//   protecting the password during transmission.
// * **Password Hashing:** NEVER store passwords in plain text.  Use a strong password hashing algorithm
//   like `password_hash()` with `PASSWORD_DEFAULT`.  This generates a one-way hash, making it impossible
//   to recover the original password even if the database is compromised.
// * **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.  Limit the number of password reset requests
//   from a single IP address or user account within a specific timeframe.
// * **Email Verification:**  Send an email to the user containing a unique, time-limited reset link.  This link should
//   be cryptographically signed to ensure its authenticity.  The user should then be prompted to enter the
//   new password on the secure reset page.  This prevents attackers from simply changing the password
//   after receiving the reset link.
// * **Error Handling and Logging:**  Implement robust error handling and logging to track password reset attempts
//   and identify potential security issues.
// * **Two-Factor Authentication (2FA):**  Consider implementing 2FA for added security.
// * **Input Validation and Sanitization:** Validate and sanitize all user input to prevent SQL injection attacks.
// * **Session Management:** Use secure session management to prevent session hijacking.
// * **Regular Security Audits:** Conduct regular security audits and penetration testing to identify and address vulnerabilities.

?>
