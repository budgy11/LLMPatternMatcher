

<?php

// Assuming you have a database connection established (e.g., $pdo)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function resetPassword(string $email, string $newPassword) {
    global $pdo; // Assuming $pdo is your database connection object

    // 1. Check if the email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 3. Update the user's password
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedNewPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        // Update failed
        return false;
    }

    return true;
}



// Example Usage (Demonstration - NOT recommended for production)
// Replace with your actual email and desired password.

//Simulating email input
// $email = $_POST['email'];  // Get email from form submission (DO NOT expose directly)
// $newPassword = $_POST['newPassword']; // Get new password from form submission (DO NOT expose directly)


// Example 1:  Successful reset
// if (isset($email) && isset($newPassword)) {
//     $success = resetPassword($email, $newPassword);
//     if ($success) {
//         echo "Password reset successfully. Please check your email.";
//     } else {
//         echo "Password reset failed. Please try again.";
//     }
// }



// Security Considerations and Best Practices (Important!)

// 1.  **Never display the new password to the user.**  This is a critical security vulnerability.
// 2. **Rate Limiting:** Implement rate limiting to prevent brute-force password reset attempts. This is crucial for security.  Limit the number of reset requests from the same IP address within a certain timeframe.
// 3. **Email Verification:**  Always send a reset link with a unique token to the user's email address. This token should be time-sensitive.  The user clicks the link to a page where they can enter the new password.
// 4. **Token-Based Reset:**  Instead of directly using the email in the `resetPassword` function, generate a unique token (e.g., a UUID) and store it in the database along with the user's email.  This token should be used to verify that the user is actually requesting the password reset and not someone who has stolen the token.
// 5. **Time-Sensitive Tokens:** Use a token that expires after a short period (e.g., 30 minutes).
// 6. **Secure Password Hashing:** Use a strong password hashing algorithm like `PASSWORD_DEFAULT` (which uses bcrypt by default) to securely store passwords in the database.
// 7. **Input Validation:** Validate the `email` and `newPassword` to prevent SQL injection and other security vulnerabilities.  Use proper escaping functions.
// 8. **Logging:** Log all password reset attempts (successful and unsuccessful) for auditing and security monitoring.

?>
