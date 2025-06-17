

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Check if the email exists
    $user = get_user_by_email($email);

    if (!$user) {
        return false; // User not found
    }

    // 2. Generate a unique token
    $token = generate_unique_token();

    // 3. Store the token and user ID in the database
    $result = save_token_for_user($user->id, $token);

    if (!$result) {
        return false; // Error saving token
    }

    // 4. Send the password reset email
    $subject = 'Password Reset';
    $message = 'Please reset your password by clicking on this link: ' . '<a href="' . generate_reset_link($token) . '" target="_blank">Reset Password</a>';
    $headers = 'From: your_website@example.com' . "\r
"; // Replace with your email address

    //  Use mail() or a more robust email sending library
    if (mail($email, $subject, $message, $headers)) {
        return true; // Reset email sent successfully
    } else {
        // Handle email sending failure (e.g., log the error)
        error_log("Failed to send password reset email to $email");
        return false;
    }
}


/**
 * Placeholder function to retrieve a user by email.  Replace with your actual database query.
 *
 * @param string $email The user's email address.
 * @return User|null  The User object if found, null otherwise.
 */
function get_user_by_email(string $email): ?User
{
    // Replace this with your actual database query.  This is just a placeholder.
    // Example:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($db, $query);
    // if (mysqli_num_rows($result) > 0) {
    //   return new User(...mysqli_fetch_assoc($result));
    // }
    // return null;

    // Dummy User class for demonstration
    class User {
        public $id;
        public $email;
        public function __construct(string $id, string $email) {
            $this->id = $id;
            $this->email = $email;
        }
    }

    // Simulate user data
    return new User(1, $email);
}



/**
 * Generates a unique token.  Consider using a more robust random number generator
 * for security.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // More secure than rand()
}



/**
 * Generates a reset link with the token.
 *
 * @param string $token The token.
 * @return string The reset link.
 */
function generate_reset_link(string $token): string
{
    return 'https://yourwebsite.com/reset_password?token=' . $token; // Replace your website URL
}

/**
 * Placeholder function to save the token for a user. Replace with your actual database operation.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token.
 * @return bool True on success, false on failure.
 */
function save_token_for_user(int $userId, string $token): bool
{
    // Replace this with your actual database operation.
    // Example:
    // $query = "UPDATE users SET token = '$token' WHERE id = $userId";
    // return mysqli_query($db, $query);

    // Dummy operation for demonstration
    return true;
}

// --- Example Usage (for demonstration only - should be in a form submission) ---
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgot_password($email)) {
        echo "Password reset email sent to $email.";
    } else {
        echo "Failed to generate password reset link.  Please try again.";
    }
}

?>
