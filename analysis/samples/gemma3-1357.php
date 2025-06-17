

<?php

// This is a simplified example for demonstration purposes only.
// In a production environment, you *must* implement robust security measures
// to prevent abuse and protect user data.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $dbHost  Database Hostname
 * @param string $dbName  Database Name
 * @param string $dbUser  Database Username
 * @param string $dbPassword Database Password
 *
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUser, string $dbPassword)
{
    // 1. Validate Input (Important!)
    if (empty($email) || empty($newPassword)) {
        return false; // Or throw an exception
    }

    // 2. Database Connection (Replace with your actual connection details)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error);
        return false;
    }

    // 3.  Check if user exists
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();



    // 4. Hash the New Password (Critical for Security!)
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 5. Update User Password
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedNewPassword, $userId);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        error_log("Failed to update password: " . $stmt->error);
        return false;
    }
}


// Example Usage (Demonstration Only - Don't use directly in a public facing app)
// $email = "testuser@example.com";
// $newPassword = "NewSecurePassword123";
// $dbHost = "localhost";
// $dbName = "your_database_name";
// $dbUser = "your_database_username";
// $dbPassword = "your_database_password";


// if (forgotPassword($email, $newPassword, $dbHost, $dbName, $dbUser, $dbPassword)) {
//     echo "Password reset email sent (simulated).";
// } else {
//     echo "Password reset failed.";
// }

?>
