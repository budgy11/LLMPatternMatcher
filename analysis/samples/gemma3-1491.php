

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token and emails a password reset
 * link to the user's email address.
 *
 * @param string $email The user's email address.
 * @param string $baseUrl The base URL of your application.  This is crucial for generating correct reset links.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log the error for debugging
        return false;
    }

    // 2. Generate a Unique Token (Time-Based)
    $token = bin2hex(random_bytes(32));  // Secure random bytes for token
    $expiry = time() + (2 * 60 * 60);  // Token expires in 2 hours

    // 3. Hash the token and email for security.
    $hashed_token = hash('sha256', $token . $email);


    // 4. Prepare the Reset Link
    $resetLink = $baseUrl . "/reset-password?token=" . urlencode($hashed_token);

    // 5. Store the Token (Temporary - This is critical for security.  Consider a better strategy for long-term storage).
    //    This example uses a simple file, but a database or a more robust temporary storage solution is highly recommended.
    $filename = "reset_tokens_" . date("Ymd") . ".txt"; // Daily file to avoid long-term storage
    $token_data = [
        'token' => $token,
        'expiry' => $expiry,
        'hashed_token' => $hashed_token
    ];

    // Check if the file exists, if not create it.
    if (!file_exists($filename)) {
        if (!file_put_contents($filename, json_encode($token_data))) {
            error_log("Failed to write reset token to file: " . $filename);
            return false;
        }
    } else {
        // If the file exists, add the new token to it
        if (!file_put_contents($filename, json_encode([
            'token' => $token,
            'expiry' => $expiry,
            'hashed_token' => $hashed_token
        ]) . PHP_EOL, FILE_APPEND)) {
            error_log("Failed to append reset token to file: " . $filename);
            return false;
        }
    }


    // 6. Send the Password Reset Email
    $subject = "Password Reset Request";
    $message = "You requested a password reset.  Click the link below to reset your password: " . $resetLink;
    $headers = "From: " . 'YourAppName@example.com' . "\r
"; // Replace with your actual email address
    
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email);
        // Optionally, delete the token if email fails (Important security measure!)
        // removeToken($token); // Implement this function to remove the token
        return false;
    }
}


/**
 *  Example function to remove the token - IMPORTANT for security.
 *  This should be called if the email fails or if a token is found to be compromised.
 *  Implement this function and use it!
 */
function removeToken(string $token) {
    //  Ideally, this would delete the token from your storage (file, database, etc.)
    //  For this example, we'll just log a message.  This is NOT sufficient in a real application.
    error_log("Removing token: " . $token);
}
?>
