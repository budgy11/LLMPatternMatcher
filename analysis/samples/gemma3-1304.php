

<?php

// Assuming you have a database connection established (e.g., $db)
// and that your users table has an 'email' and 'password' field.

// ---  DO NOT HARDCODE USER DATA IN A REAL APPLICATION!  ---
// This is just for demonstration. In a real application,
// you would get the email from the user's request.
// You also should never store passwords in plain text!
// Use a strong hashing algorithm like bcrypt.

function forgot_password($email) {
    // 1. Validate email format (basic check - more robust validation is recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the user exists
    $result = mysqli_query($db, "SELECT id, email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($result) == 0) {
        return "User not found.";
    }

    // 3. Generate a unique token for the password reset.  This is crucial for security.
    $token = bin2hex(random_bytes(32));

    // 4.  Store the token in the database associated with the user's email.
    mysqli_query($db, "UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    // 5.  Optional: Send an email with the reset link
    //  (This part is omitted for brevity and demonstration, but you should definitely implement this)
    //  -  You'll need a function to send emails.
    //  -  The email should contain a link like:  https://yourdomain.com/reset_password?token=$token

    return "Password reset link sent to your email.  Please check your inbox.";
}



// Example usage (for testing purposes - NEVER USE THIS IN PRODUCTION!)
// $email = "testuser@example.com";
// $result = forgot_password($email);
// echo $result;

// --- IMPORTANT SECURITY NOTES ---

// 1.  Hashing Passwords:  NEVER store passwords in plain text. Use bcrypt, Argon2, or another strong hashing algorithm to securely hash passwords before storing them in the database.

// 2. Token Security:
//    - Generate cryptographically random tokens (using `random_bytes()` or similar).
//    -  Tokens should be long and unpredictable.
//    -  Tokens should expire (e.g., after 30 minutes) to prevent abuse.  Implement a token expiration in your code.
//    -  Store the tokens in the database with the user's email.

// 3.  Rate Limiting:  Implement rate limiting to prevent brute-force attacks.  Limit the number of password reset requests from the same IP address or email address within a certain time period.

// 4. Input Validation:  Always validate user input rigorously to prevent SQL injection and other security vulnerabilities.  Use prepared statements or parameterized queries to prevent SQL injection.  Validate email format, length, and other relevant fields.

// 5.  Email Sending: Use a reliable email sending service (e.g., SendGrid, Mailgun) instead of trying to send emails directly from your PHP script, which can lead to deliverability issues and potential security risks.

// 6. Session Management: Securely manage user sessions and cookies to prevent session hijacking.
// 7. HTTPS: Always use HTTPS to encrypt the communication between the user's browser and your server.
?>
