

<?php

/**
 *  Forgot Password Function
 *
 *  This function generates a unique token, sends an email with a reset link,
 *  and updates the user's password reset status in the database.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password for the user.
 * @param string $token  A unique token generated for the reset link. (Generated internally)
 * @param PDO $pdo      The PDO database connection object.
 *
 * @return bool True if the password reset process was initiated successfully,
 *              false otherwise (e.g., email sending failure, invalid email).
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo)
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random function
    //  'bin2hex' converts the byte array from random_bytes() into a hexadecimal string.
    //  This is a standard way to create a unique token.


    // 2.  Prepare the SQL Query
    $sql = "UPDATE users SET password = :password, password_reset_token = :token, password_reset_token_expiry = :expiry, password_reset_status = 'valid' WHERE email = :email";

    // 3. Prepare the statement
    $stmt = $pdo->prepare($sql);


    // 4. Bind Parameters
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + (60 * 60 * 24))); // Expire after 24 hours
    $stmt->bindParam(':email', $email);


    // 5. Execute the Query
    if ($stmt->execute()) {
        // 6. Generate the Reset Link (Email Content)
        $resetLink = "https://yourwebsite.com/reset-password?token=" . $token;  // Replace yourwebsite.com

        // 7.  Send the Email (Implement your email sending logic here)
        $to = $email;
        $subject = 'Password Reset';
        $message = "Click the link below to reset your password:
" . $resetLink;
        $headers = 'From: yourwebsite@example.com' . "\r
";

        if (mail($to, $subject, $message, $headers)) {
            // Email sent successfully.
            return true;
        } else {
            // Email sending failed
            // Optionally log the error here.  Crucial for debugging.
            error_log("Error sending password reset email for " . $email);
            return false;
        }
    } else {
        // Query failed - likely an invalid email or other database issue
        error_log("Error updating password for " . $email);
        return false;
    }
}


// Example Usage (Replace with your actual database connection)
//  Important:  This is just an example.  You MUST adapt this to your specific setup.

// Dummy database connection (replace with your actual connection)
$host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


$email = 'testuser@example.com';  // Replace with the user's email
$newPassword = 'newSecurePassword123';  // Replace with the new password

if (forgotPassword($email, $newPassword, $pdo)) {
    echo "Password reset email sent successfully!";
} else {
    echo "Failed to initiate password reset.";
}

?>
