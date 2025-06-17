

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset token was generated and sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email);
        return false;
    }

    // 2. Check if user exists
    $user = getUserById($email); // Assuming you have a function to retrieve a user by email.
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique reset token
    $resetToken = generateUniqueToken();

    // 4. Store the token in the database, associated with the user's ID
    $result = storeResetToken($user['id'], $resetToken);
    if (!$result) {
        error_log("Failed to store reset token for user " . $email);
        return false;
    }

    // 5. Send the password reset email (using a separate email sending function)
    if (!sendPasswordResetEmail($user['email'], $resetToken, 'your_website_url')) { // Replace 'your_website_url'
        error_log("Failed to send password reset email to " . $email);
        // Optionally, you might want to delete the token from the database in case of failure.
        // deleteResetToken($user['id'], $resetToken);
        return false;
    }


    return true;
}

/**
 * Placeholder function to retrieve a user by email.  You'll need to implement this.
 * @param string $email
 * @return array|null  The user object if found, null otherwise.
 */
function getUserById(string $email): ?array
{
    // Replace this with your database query to retrieve the user by email
    // Example (using mysqli - adapt to your database):
    // $query = "SELECT * FROM users WHERE email = '" . $email . "'";
    // $result = mysqli_query($db, $query);
    // if (mysqli_num_rows($result) > 0) {
    //     $user = mysqli_fetch_assoc($result);
    //     return $user;
    // } else {
    //     return null;
    // }

    //  Return a dummy user for example purposes:
    return [
        'id' => 123,
        'email' => $email,
        'username' => 'testuser'
    ];
}


/**
 * Placeholder function to generate a unique token.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator.
}


/**
 * Placeholder function to store the reset token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The reset token.
 * @return bool True if the token was stored successfully, false otherwise.
 */
function storeResetToken(int $userId, string $token): bool
{
    // Replace this with your database query to store the token.
    // Example (using mysqli):
    // $query = "INSERT INTO reset_tokens (user_id, token, created_at) VALUES (" . $userId . ", '" . $token . "', NOW())";
    // if (mysqli_query($db, $query)) {
    //     return true;
    // } else {
    //     error_log("Failed to store reset token in database: " . mysqli_error($db));
    //     return false;
    // }

    //Dummy success for example
    return true;
}

/**
 * Placeholder function to send the password reset email.
 *
 * @param string $email The email address of the user.
 * @param string $token The reset token.
 * @param string $websiteUrl The URL of the website.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendPasswordResetEmail(string $email, string $token, string $websiteUrl): bool
{
    // Replace this with your email sending logic.
    // Example (using mail() - this is often unreliable):
    // $subject = 'Password Reset Request';
    // $message = "Click this link to reset your password: " . $websiteUrl . "?token=" . $token;
    // return mail($email, $message, $subject);

    //Dummy success for example
    return true;
}


/**
 * Placeholder function to delete a reset token.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function deleteResetToken(int $userId, string $token): bool{
    //Replace with your database query
    return true;
}

?>
