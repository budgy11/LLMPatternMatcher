

<?php

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUsername The database username.
 * @param string $dbPassword The database password.
 *
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUsername, string $dbPassword) {
    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_SILENT); // Suppress errors

        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedNewPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true; // Password reset successful
        } else {
            return false; // No user found with that email
        }

    } catch (PDOException $e) {
        // Handle database errors (important for debugging and security)
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false; // Indicate failure
    }
}


// Example Usage (Remember to replace with your actual database details)
//  This is just an example, and should *not* be used in a production environment
//  without proper security measures and validation.
/*
$email = "test@example.com";
$newPassword = "NewPassword123!";

$dbHost = "localhost";
$dbName = "your_database_name";
$dbUsername = "your_username";
$dbPassword = "your_password";

if (forgotPassword($email, $newPassword, $dbHost, $dbName, $dbUsername, $dbPassword)) {
    echo "Password reset email sent successfully.  Check the user's email.";
} else {
    echo "Failed to reset password.  Check the user's email and database connection.";
}
*/

?>
