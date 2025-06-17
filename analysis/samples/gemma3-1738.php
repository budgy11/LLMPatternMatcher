

<?php

// Assuming you have a database connection established and a 'users' table 
// with a 'email' and 'password' column.

// Function to handle password reset requests
function forgotPassword($email, $newPassword, $dbConnection) {
    // 1. Validate Email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize email
    if (empty($email)) {
        return "Invalid email address.";
    }

    // 2. Check if the user exists
    $stmt = $dbConnection->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($user)) {
        return "User not found.";
    }

    // 3. Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 4. Update the user's password
    try {
        $stmt = $dbConnection->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedNewPassword, $user['id']]);
        return "Password reset successful. Please check your email for instructions.";

    } catch (PDOException $e) {
        return "Error resetting password: " . $e->getMessage(); // Handle errors
    }
}


// Example usage (assuming $dbConnection is already established)
// echo forgotPassword("user@example.com", "newpassword123", $dbConnection);


//  Important Considerations & Security Best Practices:

// 1.  Database Connection:  Replace `$dbConnection` with your actual database connection object. This example uses PDO for database interaction.

// 2.  Password Hashing:
//   - Use `password_hash()` to securely hash the new password *before* storing it in the database. This is crucial for security.
//   - `PASSWORD_DEFAULT` is a good default algorithm.  Consider using a more modern algorithm if your database supports it.

// 3.  Email Sending:
//    -  This code *does not* include email sending.  You'll need to add code to send an email with a password reset link.  The link should contain a token or unique identifier to verify the user's request.  Use a library or service like PHPMailer to handle email sending.
//    -  **Important:**  The reset link should expire after a certain period (e.g., 30 minutes, 1 hour) to prevent unauthorized access.

// 4. Token-Based Reset Links (Recommended):
//   -  Instead of simply sending the password to the email, generate a unique token (e.g., a UUID) and include it in the reset link.
//   -  When the user clicks the link, the token is verified on the server.
//   -  This ensures that only the user who requested the reset can actually reset their password.

// 5.  Input Validation:
//    -  Sanitize and validate all user inputs (email, newPassword) thoroughly to prevent SQL injection and other security vulnerabilities.  `filter_var()` is a good starting point, but consider using a more robust input validation library.

// 6. Error Handling:
//    -  Use `try...catch` blocks to handle potential errors during database operations.

// 7. CSRF Protection:
//    -  Implement CSRF (Cross-Site Request Forgery) protection to prevent malicious websites from triggering password reset requests on your site.

// 8. Logging:
//    - Log all password reset attempts (successful or failed) for auditing and security monitoring.

// 9.  Rate Limiting:
//     - Limit the number of password reset requests a user can make within a certain time period to prevent brute-force attacks.

?>
