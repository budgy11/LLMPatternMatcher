

<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect("localhost", "username", "password", "database_name");)

// Function to reset password
function forgot_password($email) {
    // 1. Generate a unique, secure token
    $token = bin2hex(random_bytes(32));

    // 2. Store the token and expiration time in the database
    //   (Important:  Always expire tokens after a certain duration.)
    //  This example assumes you have a 'users' table with 'email' and 'password' columns
    //  and a 'reset_tokens' table with 'email' and 'token' columns.
    //  You'll need to adapt this part to your specific database schema.

    // Check if the email already has a reset token
    $stmt = $db->prepare("SELECT id FROM reset_tokens WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token already exists.  Update the existing token
        $stmt = $db->prepare("UPDATE reset_tokens SET token = ?, expiration = NOW() WHERE email = ? AND token = ?");
        $stmt->bind_param("ss", $token, $email, $token); // token is used as a placeholder to update
        $result = $stmt->execute();

        if ($result) {
            // Success:  Send an email (implementation not included - see below)
            //  You would typically use a mail function or an email library.
            echo "Password reset link generated. Check your email for a reset link.";
        } else {
            // Handle error
            error_log("Error updating reset token: " . $db->error);
            echo "Error updating token. Please try again.";
        }
    } else {
        // 1. Insert a new reset token into the database
        $stmt = $db->prepare("INSERT INTO reset_tokens (email, token, expiration) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $token);

        if ($stmt->execute()) {
            // 2. Send an email to the user with the reset link
            $reset_url = "https://yourwebsite.com/reset_password?token=" . $token; // Replace with your actual URL

            //  Implementation for sending the email (simplified example - customize!)
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password: " . $reset_url;
            $headers = "From: yourwebsite@example.com"; // Replace with your email address

            // Use mail() function (may require configuration)
            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset link generated. Check your email for a reset link.";
            } else {
                // Handle error
                error_log("Error sending email: " . mail($email, $subject, $message, $headers));
                echo "Error sending email. Please check your email settings.";
            }

        } else {
            // Handle error
            error_log("Error inserting reset token: " . $db->error);
            echo "Error generating reset token. Please try again.";
        }
    }

    $stmt->close();
    $result->close();
}

// Example usage (This is just a demonstration, don't use it directly in production)
// Replace 'user@example.com' with the actual email address
// forgot_password('user@example.com');


// **Important Security Considerations and Improvements:**

// 1. **Salt and Hash Passwords:**  Never store passwords in plain text. Always hash them using a strong hashing algorithm (e.g., bcrypt, Argon2) before storing them in the database.  This function assumes you're already doing that for the user's password.

// 2. **Token Expiration:**  Tokens *must* expire after a certain period (e.g., 1 hour, 12 hours). This prevents attackers from using old, potentially compromised tokens.  The `expiration = NOW()` sets the expiration time.

// 3. **Token Uniqueness:** Ensure the generated tokens are truly unique. `random_bytes(32)` provides a high degree of uniqueness.

// 4. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks on the password reset endpoint.  Limit the number of reset requests from the same IP address within a given time frame.

// 5. **Input Validation and Sanitization:** Always validate and sanitize user input (email address, etc.) to prevent SQL injection and other security vulnerabilities.

// 6. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

// 7. **Error Handling:**  Implement robust error handling and logging to help you debug issues and identify potential security breaches.  Don't display detailed error messages to the user in a production environment.

// 8. **Email Sending Implementation:**  The `mail()` function is often unreliable across different servers and configurations. Consider using a dedicated email library or service (e.g., PHPMailer, SwiftMailer, SendGrid, Mailgun) for more reliable email sending.

// 9. **Security Audits:** Regularly conduct security audits to identify and address potential vulnerabilities.

?>
