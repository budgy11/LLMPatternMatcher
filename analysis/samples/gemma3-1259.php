

<?php

// This is a simplified example.  In a real-world application,
// you would want to implement proper security measures 
// such as:
// 1.  Using a more robust password reset mechanism (e.g., token-based)
// 2.  Storing passwords securely (e.g., using bcrypt or Argon2)
// 3.  Implementing rate limiting to prevent brute-force attacks
// 4.  Logging all password reset attempts
// 5.  Adding two-factor authentication

/**
 * Resets a user's password.  This is a simplified example and should
 * be adapted for production use.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password for the user.
 * @param PDO $pdo  The database connection object.
 *
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo)
{
    // Validate email and password (basic validation - improve for production)
    if (empty($email) || empty($newPassword)) {
        return false;
    }

    // Check if the user exists
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // User not found
    }

    // Hash the new password (important for security!)
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedNewPassword);
    $stmt->bindParam(':id', $user['id']);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        return false; // Failed to update password
    }

    return true; // Password reset successful
}


// Example Usage (For testing - DO NOT USE THIS DIRECTLY IN A PUBLIC WEBSITE)
// This example assumes you have a database connection called $pdo

// Create a dummy database connection for demonstration
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_db_user';
$password = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Example User data (replace with your actual database)
$userEmail = 'test@example.com'; // Replace with a valid test email
$newPassword = 'newPassword123';

if (forgotPassword($userEmail, $newPassword, $pdo)) {
    echo "Password reset successful for $userEmail!";
} else {
    echo "Password reset failed for $userEmail.";
}

// Close the database connection (important!)
$pdo = null;


?>
