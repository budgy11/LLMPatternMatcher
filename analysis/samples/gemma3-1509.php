

<?php

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @param string $dbHost     The database host.
 * @param string $dbName     The database name.
 * @param string $dbUser     The database username.
 * @param string $dbPassword The database password.
 *
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUser, string $dbPassword)
{
    // 1. Connect to the database
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    // 2. Check the connection
    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error); // Log the error
        return false;
    }

    // 3.  **IMPORTANT:**  Implement a secure password hashing function.
    //   Using a simple password hash like md5 is HIGHLY discouraged.  Use password_hash()
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 4.  Update the password for the user
    $sql = "UPDATE users SET password = '$hashedNewPassword' WHERE email = '$email'"; //  Use single quotes for string values
    $result = $conn->query($sql);

    // 5. Check the result
    if (!$result) {
        error_log("Query failed: " . $conn->error); // Log the error
        return false;
    }

    // 6.  Success!  (You might want to send an email notification here)
    return true;
}


// Example Usage (Illustrative - Replace with your actual values)
// DO NOT include this in a production environment without proper input sanitization and validation!

// $email = 'test@example.com';
// $newPassword = 'NewSecurePassword123!';
// $dbHost = 'localhost';
// $dbName = 'your_database_name';
// $dbUser = 'your_username';
// $dbPassword = 'your_password';

// if (forgot_password($email, $newPassword, $dbHost, $dbName, $dbUser, $dbPassword)) {
//     echo "Password reset successful!";
// } else {
//     echo "Password reset failed.";
// }
?>
