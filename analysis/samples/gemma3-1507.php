

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 *  Generates a reset token and updates the user's record with it.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email (Basic)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email);  // Log the error for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserById($email); // Replace with your function to get user by email
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique reset token
    $token = generate_unique_token();

    // 4. Hash the token for security
    $hashed_token = hash('sha256', $token);  // Using SHA256 for stronger hashing

    // 5. Update the user's record with the token
    if (!updateUserToken($user['id'], $hashed_token)) {
        error_log("Failed to update user token: " . $email);
        return false;
    }

    // 6.  Send the password reset email (implementation details are not included here; see below for example)

    // 7. Return true to indicate success
    return true;
}

/**
 * Placeholder function to retrieve a user by email.  Replace with your actual database query.
 *
 * @param string $email The email address of the user to retrieve.
 * @return array|null An associative array representing the user, or null if not found.
 */
function getUserById(string $email): ?array
{
    // Replace this with your actual database query
    // Example:
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //     return mysqli_fetch_assoc($result);
    // }
    // return null;

    // Mock implementation for demonstration
    $mock_users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'hashed_password']
    ];
    foreach ($mock_users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}



/**
 * Placeholder function to generate a unique token.  You should use a more robust method for security.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Using random_bytes for better randomness
}


/**
 * Placeholder function to update the user's token.  Replace with your actual database query.
 *
 * @param int $userId The ID of the user to update.
 * @param string $hashedToken The hashed token to store.
 * @return bool True on success, false on failure.
 */
function updateUserToken(int $userId, string $hashedToken): bool
{
   // Replace this with your actual database query
   // Example:
   // $sql = "UPDATE users SET token = '$hashedToken' WHERE id = $userId";
   // $result = mysqli_query($db, $sql);
   // return (mysqli_affected_rows($result) > 0);

   // Mock implementation for demonstration
   $user = getUserById($userId);
   if ($user) {
       $user['token'] = $hashedToken;
       return true;
   }
   return false;
}



/**
 * Placeholder function to send a password reset email.
 * This function is just a placeholder, and you will need to implement the actual email sending logic.
 *
 * @param string $email The email address of the user.
 * @param string $resetToken The reset token.
 * @return void
 */
function send_password_reset_email(string $email, string $resetToken): void
{
    // Implement your email sending logic here (e.g., using PHPMailer)
    // Example:
    // $subject = "Password Reset Request";
    // $body = "Click this link to reset your password: " . URL . "/reset-password?token=" . $resetToken;
    // $headers = "From: noreply@example.com" . "\r
";
    // mail($email, $subject, $body, $headers);
    error_log("Password reset email not implemented.  Send password reset email to " . $email . " with token " . $resetToken);
}


// Example Usage (Illustrative Only -  replace with real interaction)
$email_to_reset = "test@example.com"; // Replace with an actual email address.

if (forgot_password($email_to_reset)) {
    echo "Password reset request sent for " . $email_to_reset . ". Check their email!";
    //  Now you need to implement the logic to store the $resetToken in the database
    //  and also trigger sending the email.

} else {
    echo "Failed to initiate password reset for " . $email_to_reset;
}

?>
