

<?php

// IMPORTANT:  This code is for demonstration purposes only. 
// In a production environment, you absolutely MUST:
// 1.  Securely store your database credentials (e.g., using environment variables).
// 2.  Implement robust security measures like email verification, rate limiting,
//     and thorough input validation to prevent unauthorized access and attacks.
// 3.  Consider using a dedicated password reset library or service for enhanced security.

// Database connection details (Replace with your actual credentials!)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_database_user';
$dbPass = 'your_database_password';

// Email configuration (Replace with your email server details)
$emailFrom = 'your_email@example.com';
$emailTo = '%email%';  //  Placeholder for the user's email address
$emailSubject = 'Password Reset';
$emailHeaders = "From: $emailFrom\r
";
$emailBody = "To reset your password, please click on the following link: 
";
$emailBody .= '<a href="reset_password.php?token=' . generateToken() . '" >Reset Password</a>';
//  Note:  This link will expire after a certain time.


/**
 * Generates a unique, secure token for password reset.
 *
 * @return string A unique token.
 */
function generateToken() {
    return bin2hex(random_bytes(32)); //  32 bytes (256 bits) for a strong token
}

/**
 * Checks if a user exists in the database.
 *
 * @param string $email The user's email address.
 * @return bool True if the user exists, false otherwise.
 */
function userExists($email) {
    global $dbHost, $dbName, $dbUser, $dbPass;

    // Use prepared statement to prevent SQL injection.  IMPORTANT!
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // 's' indicates a string parameter

    if ($stmt->execute() === false) {
        die('Error executing query: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
    $conn->close();
}



/**
 * Resets the user's password.  (This is a placeholder; handle securely!)
 *
 * **WARNING: DO NOT directly use user input to set the password.  
 * This is an extremely vulnerable function in a real application!**
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function resetPassword($email, $newPassword) {
    global $dbHost, $dbName, $dbUser, $dbPass;

    // Use prepared statement to prevent SQL injection
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", hash('argon2', $newPassword), $email);  // Using argon2 for hashing
    
    if ($stmt->execute() === false) {
        die('Error executing query: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    return true;
}




// Example usage (Demonstration -  Do not directly use in a live application)
//  This is just for illustrating how the functions would be called.
if (isset($_POST['email']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // IMPORTANT: Input Validation and Sanitization are CRITICAL here!
    //  Validate email format, check password length, etc.

    if (userExists($email)) {
        resetPassword($email, $newPassword);

        echo "Password reset link has been sent to your email address.";
    } else {
        echo "User with this email does not exist.";
    }
}

// Example HTML form for initiating the password reset:
?>
