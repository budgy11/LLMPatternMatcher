

<?php

// Assuming you have a database connection established (e.g., $pdo)

function forgotPassword($email, $password) {
    // **Important Security Considerations:**

    // 1. Sanitize and Validate Input:  ALWAYS sanitize and validate user input.  This example doesn't fully demonstrate this for brevity, but it's crucial.
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);  // Sanitize email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Use Strong Hashing: Never store passwords in plain text. Use a strong hashing algorithm like bcrypt or Argon2.
    // This example uses a placeholder - replace with a real hashing function!
    $hashedPassword = hash('argon2', $password, SALT);  // Replace 'SALT' with your actual salt

    // 3.  Rate Limiting:  Implement rate limiting to prevent brute-force attacks.
    // 4.  Email Sending:  Use a reliable email service (e.g., Mailgun, SendGrid) for sending password reset emails.  Don't use `mail()` directly, as it's often unreliable and doesn't handle attachments well.

    try {
        // Example database query (replace with your actual database structure and query)
        $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = :reset_token, reset_token_expiry = :expiry WHERE email = :email");
        $stmt->execute([
            'password' => $hashedPassword,
            'reset_token' => generateUniqueToken(),
            'expiry' => date('Y-m-d H:i:s', time() + 3600), // Token expires in 1 hour
            'email' => $email
        ]);

        if ($stmt->rowCount() === 0) {
            return "User not found.";
        }

        // Send password reset email
        $resetLink = "https://yourwebsite.com/reset-password?token=" . $stmt->fetchColumn(); // Replace with your actual website URL

        // You'd normally use an email service here to send the email.
        // This is a placeholder:
        $subject = "Password Reset";
        $message = "Click the link below to reset your password: " . $resetLink;
        $headers = "From: yourwebsite@example.com"; // Replace with your email address
        // mail($email, $subject, $message, $headers);

        return "Password reset email sent. Check your inbox.";

    } catch (PDOException $e) {
        // Handle database errors gracefully
        return "Error: " . $e->getMessage();
    }
}

// Helper function to generate a unique token (replace with a stronger method!)
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // More secure than simple random numbers
}


// Example Usage (for demonstration only - don't use this directly in a public-facing application)
// $email = "test@example.com";
// $newPassword = "MyNewSecurePassword123";
// $result = forgotPassword($email, $newPassword);
// echo $result;
?>
