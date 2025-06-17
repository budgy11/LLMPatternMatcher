

<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 *
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword)
{
    // Validate email (basic check - adjust as needed for your application)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email);  // Log the error for debugging
        return false;
    }


    // 1. Check if the user exists
    $user = getUserById($email); // Assume this function fetches user by email

    if (!$user) {
        error_log("User not found with email: " . $email);
        return false;
    }


    // 2. Hash the new password (VERY IMPORTANT - never store passwords in plain text!)
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);


    // 3. Update the user's password in the database
    $result = updateUserPassword($user->id, $hashedPassword); // Assume this updates password based on user ID

    if (!$result) {
        error_log("Failed to update password for user: " . $email);
        return false;
    }

    return true;
}


/**
 * Placeholder functions (implement these based on your database structure)
 */

/**
 * Fetches a user by their email address.
 *
 * @param string $email The email address of the user.
 *
 * @return User | null User object if found, null otherwise.  (Adjust the User class as needed)
 */
function getUserById(string $email)
{
    // Replace this with your actual database query
    // Example using MySQLi (adapt to your database type)
    $db = getDatabaseConnection(); // Assume this function gets your database connection

    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_object();
        return $user;
    }

    return null;
}


/**
 * Updates a user's password in the database.
 *
 * @param int $userId The ID of the user to update.
 * @param string $hashedPassword The newly hashed password.
 *
 * @return bool True if the update was successful, false otherwise.
 */
function updateUserPassword(int $userId, string $hashedPassword)
{
    // Replace this with your actual database query
    // Example using MySQLi (adapt to your database type)
    $db = getDatabaseConnection();

    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("ss", $hashedPassword, $userId);  // 'ss' indicates two string parameters

    $result = $stmt->execute();

    // Check if the query executed successfully
    if ($result === false) {
        error_log("Error updating password: " . $stmt->error);
        return false;
    }

    return true;
}


// Example usage (for testing - remove or adjust before deploying)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["newPassword"];

    if (isset($email) && isset($newPassword)) {
        if (forgotPassword($email, $newPassword)) {
            echo "Password reset email has been sent.  Check your inbox.";
        } else {
            echo "Failed to reset password.  Please try again.";
        }
    } else {
        echo "Invalid request. Please provide email and new password.";
    }
}

?>
