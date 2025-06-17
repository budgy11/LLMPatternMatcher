

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'password' column.  You'll need to adapt this to your setup.

// Database credentials (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_db_name';

// Function to reset password
function forgotPassword($email, $password_reset_token, $expiration_time_minutes = 60) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    // 2. Generate a Token
    $token = bin2hex(random_bytes(32));  // Generate a strong, random token

    // 3.  Hash the Token (Important for security!)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the Token and Expiration Time in the database.
    //  Replace this with your actual database query
    try {
        // Example using mysqli (adapt for your database library)
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO password_resets (email, token, expiration_time)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $email, $hashed_token, time() + $expiration_time_minutes);
        $stmt->execute();

        $stmt->close();

    } catch (Exception $e) {
        // Handle database errors - log them, display a generic error, etc.
        error_log("Database error: " . $e->getMessage());
        return false; // Indicate failure
    }

    // 5.  Return success (true) and the token for display to the user.
    return ['success' => true, 'token' => $token];
}

// Example Usage (demonstration)
// Assuming you have a form that collects the email address.
// This is a simplified example; in a real application, you'd handle form submission securely.

// Simulate receiving an email address
$email = 'testuser@example.com';

// Call the forgotPassword function
$result = forgotPassword($email);

if ($result['success']) {
    echo "<p>Password reset link sent to " . $email . " successfully.</p>";
    echo "<p>Click the link in the email to set a new password.</p>";
} else {
    echo "<p>Failed to generate password reset token.</p>";
    //  You might log the error here for debugging.
}


// --------------------------------------------------------------------
//  Important Considerations & Next Steps:
// --------------------------------------------------------------------
// 1.  Security:
//    -  Password Hashing:  Never store passwords in plain text.  Always use a strong hashing algorithm like `password_hash()` with a suitable cost factor (PASSWORD_DEFAULT).
//    -  Token Generation: Use `random_bytes()` to generate cryptographically secure random tokens.
//    -  HTTPS:  Always serve your website over HTTPS to encrypt the communication and protect user data during transmission.
//    -  Rate Limiting: Implement rate limiting to prevent brute-force attacks.
//    -  Input Validation and Sanitization:  Thoroughly validate and sanitize all user input to prevent SQL injection and other vulnerabilities.
//
// 2.  Database Integration:  Replace the placeholder database code with your actual database connection and query logic.
// 3.  Email Sending:  You'll need to integrate with an email service (e.g., PHPMailer, SwiftMailer, or a third-party email API) to send the password reset email.  *Don't* use a simple `mail()` function, as it is not reliable and can be easily blocked.
// 4.  Token Validation:  In your `forgot_password_confirm` function (which you'll need to create to handle the user's confirmation), you'll need to:
//    -  Verify that the token exists in the database.
//    -  Check if the token has expired (i.e., the `expiration_time` is in the past).
//    -  Hash the token received from the user and compare it to the hashed token in the database.
//    -  If all checks pass, allow the user to set a new password.
//
// 5.  Error Handling:  Implement robust error handling and logging to identify and address issues.
// 6.  User Experience: Provide clear and helpful instructions to the user.

?>
