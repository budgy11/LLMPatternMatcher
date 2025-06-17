

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email);
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a Unique Token and Store It
    $token = generateUniqueToken();
    $hashedToken = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

    // 4. Store Token in Database (Associate with User)
    if (!storeTokenInDatabase($user->id, $hashedToken)) {
        error_log("Failed to store token in database for user " . $email);
        return false;
    }

    // 5.  Send Password Reset Email
    if (!sendPasswordResetEmail($user->email, $token)) {
        // If sending email fails, you might want to rollback the token in the database.
        //  This depends on your application's requirements.  For example:
        //  deleteTokenFromDatabase($user->id);
        error_log("Failed to send password reset email for user " . $email);
        return false;
    }

    return true;
}


/**
 *  Helper function to get user by email
 *  This is a placeholder, replace with your actual database query.
 *
 * @param string $email
 * @return User|null
 */
function getUserByEmail(string $email): ?User {
  // Replace this with your actual database query
  // Example using a hypothetical User model:
  // $query = "SELECT * FROM users WHERE email = ?";
  // $stmt = $db->prepare($query);
  // $stmt->execute([$email]);
  // $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // if ($user) {
  //   return new User($user);
  // }

  // Placeholder return
  return null;
}



/**
 * Generates a unique token.  Consider using a cryptographically secure random number generator.
 *
 * @return string
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32));  // Use bin2hex for a hexadecimal representation
}


/**
 *  Helper function to store the token in the database
 *  Replace with your actual database code
 *
 * @param int $userId
 * @param string $hashedToken
 */
function storeTokenInDatabase(int $userId, string $hashedToken) {
    // Replace this with your database code to store the token for the user.
    // Example (using a hypothetical database table 'password_tokens'):
    // $query = "INSERT INTO password_tokens (user_id, token) VALUES (?, ?)";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$userId, $hashedToken]);
    // return true;

    // Placeholder - always returns true for now
    return true;
}


/**
 *  Helper function to send a password reset email
 *  Replace with your actual email sending logic
 *
 * @param string $email
 * @param string $token
 */
function sendPasswordResetEmail(string $email, string $token) {
    // Replace this with your actual email sending logic.
    // This is just a placeholder.

    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . urlencode($token);
    $headers = "From: yourdomain@example.com\r
";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Example usage (for demonstration only - don't use in production without proper sanitization)
// $email = "test@example.com"; // Replace with the user's email
// if (forgotPassword($email)) {
//     echo "Password reset email sent successfully!";
// } else {
//     echo "Failed to send password reset email.";
// }
?>
