

<?php

// This is a simplified example and should be adapted for a real-world application.
// Consider using a proper database and session handling for security.

// IMPORTANT:  Never store passwords in plain text!  This example uses hashing and salting for demonstration only.
// In a production environment, use a strong password hashing algorithm like bcrypt or Argon2.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @param array $db  Database connection (or database object)
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, array $db) {
    // 1. Validate Email
    if (empty($email)) {
        error_log("Forgot Password: Invalid email address.");  // Log the error for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email, $db);
    if ($user === null) {
        error_log("Forgot Password: User not found for email: " . $email); // Log for debugging
        return false;
    }

    // 3.  Hash the New Password - VERY IMPORTANT for security!
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 4. Update User's Password
    $result = updatePassword($user['id'], $hashedNewPassword, $db);

    if ($result === true) {
        // Password update successful
        return true;
    } else {
        // Password update failed. Log the error for debugging.
        error_log("Forgot Password: Failed to update password for user: " . $email);
        return false;
    }
}

/**
 * Helper function to get user by email.
 *
 * @param string $email The email address to search for.
 * @param array $db  Database connection (or database object)
 *
 * @return array|null User data if found, null otherwise.
 */
function getUserByEmail(string $email, array $db) {
    $stmt = $db['query']("SELECT id, username, email FROM users WHERE email = ?", [$email]);

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchArray(PDO::FETCH_ASSOC);
    }
    return null;
}

/**
 * Helper function to update a user's password.
 *
 * @param int $userId The ID of the user to update.
 * @param string $hashedPassword The hashed new password.
 * @param array $db Database connection (or database object)
 *
 * @return bool True on success, false on failure.
 */
function updatePassword(int $userId, string $hashedPassword, array $db) {
    $sql = "UPDATE users SET password = ?, hashed_password = ? WHERE id = ?";
    $stmt = $db['query']($sql, [$hashedPassword, $hashedPassword, $userId]);

    return ($stmt->rowCount() === 1); // Check if exactly one row was affected.
}



// Example Usage (for demonstration only - never put this in your public-facing code)
// This is just to demonstrate how the function is called.  It's not meant to be run directly.
// You would typically call this function through a form submission.

// Mock database connection (replace with your actual database setup)
$db = [
    'query' => function($sql, $params) {
        // Simulate a PDO query - replace with your actual database connection.
        // This is a basic example; a real application should use a robust database connection.
        try {
            $conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'user', 'password');
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Handle database errors appropriately (logging, error page, etc.)
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }
];

// Simulate a form submission
$email = 'test@example.com';
$newPassword = 'MyNewPassword123';


if (forgotPassword($email, $newPassword, $db)) {
    echo "Password reset email sent.  Check your inbox!";
} else {
    echo "Password reset failed.  Please try again.";
}


?>
