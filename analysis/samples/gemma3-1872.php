    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db_connection)
// This is just a basic example, adapt to your database setup

function forgotPassword($email) {
    // 1. Check if the email exists in the database
    $stmt = $db_connection->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email not found
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));  // Using random_bytes for better security

    // 3. Hash the token (for security) - This is CRUCIAL!
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the token in the database for the user
    $stmt = $db_connection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->execute([$hashed_token, time() + 3600, $email]); // Store expiry 1 hour from now

    // 5.  Email the user with a reset link
    $reset_link = "http://yourwebsite.com/reset-password?token=$token"; // Replace with your website URL

    // Email the user
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . $reset_link;
    $headers = "From: yourwebsite@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    return true;
}


// Example usage (assuming you've called forgotPassword() with an email)
// $email_to_reset = "testuser@example.com"; // Replace with the user's email
// $result = forgotPassword($email_to_reset);

// if ($result) {
//     echo "Password reset email has been sent.";
// } else {
//     echo "User not found or email reset link could not be generated.";
// }


//Important Security Notes & Enhancements:

// 1.  Database Connection:  Replace `$db_connection` with your actual database connection object.  This example assumes you're using PDO.

// 2.  Error Handling:  Add more robust error handling.  Check the return values of your database queries.  Log errors.

// 3.  Password Hashing:  The `password_hash()` function is critical.  Never store passwords in plain text.

// 4.  Token Uniqueness and Randomness:  `random_bytes()` is used to generate the token.  This is the most secure method for generating random data.  Using `mt_rand()` or `rand()` is generally not recommended for security-sensitive operations.

// 5.  Token Expiry:  The `reset_token_expiry` field and `time() + 3600` (one hour) are added to prevent tokens from being used indefinitely. Adjust the expiry time as needed.  Consider logging expiry events.

// 6.  Rate Limiting: Implement rate limiting on password reset requests to prevent brute-force attacks.  Store the number of reset attempts per user and block access if the limit is exceeded.

// 7.  Secure URL:  The `reset_link` URL should be generated securely.  Don't include the token directly in the URL string in a production environment.  Use URL encoding or a more secure method.

// 8.  Input Validation:  Validate the email address to prevent malicious input.

// 9.  Security Audits: Regularly perform security audits of your code to identify and address vulnerabilities.

// 10. CSRF Protection: Implement CSRF (Cross-Site Request Forgery) protection, especially if you are generating the reset link on a form submission.

// 11. Logging:  Log password reset requests, including the user's email, the token generated, and the timestamp. This is important for debugging and security monitoring.
?>
