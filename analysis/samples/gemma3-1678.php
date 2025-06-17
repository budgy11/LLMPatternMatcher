

<?php

/**
 * Forgets a user's password and sends them a reset link.
 *
 * @param string $email The email address of the user.
 * @param string $reset_token A unique token to verify the request.
 * @param string $secret_key The secret key used to generate the token.
 * @param string $new_password The new password.
 *
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password(string $email, string $reset_token, string $secret_key, string $new_password): bool
{
    // 1. Verify the reset token
    $hashed_token = hash('sha256', $reset_token . $secret_key);

    if (!password_verify($hashed_token, $reset_token)) {
        // Token is invalid
        return false;
    }

    // 2.  Update the user's password
    try {
        $user = \App\Models\User::where('email', $email)->first(); // Adjust 'App\Models\User' to your actual user model

        if ($user) {
            $user->password = password_hash($new_password, PASSWORD_DEFAULT);
            $user->save();
            return true;
        } else {
            // User not found
            return false;
        }
    } catch (\Exception $e) {
        // Handle exceptions (e.g., database errors)
        error_log("Error during password reset: " . $e->getMessage());
        return false;
    }
}

// Example Usage (Demonstration)

// In a real application, you'd generate this token and email it to the user.
// This is just for illustration.
$email = 'test@example.com';
$reset_token = 'some_unique_token_123';
$secret_key = 'your_secret_key'; // Keep this secret!
$new_password = 'new_password123';

$reset_successful = forgot_password($email, $reset_token, $secret_key, $new_password);

if ($reset_successful) {
    echo "Password reset successful! Check your email for instructions.";
} else {
    echo "Password reset failed.  Check your email or try again.";
}

?>
