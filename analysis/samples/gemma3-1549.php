

<?php

// IMPORTANT: This is a simplified example for demonstration purposes.
// In a production environment, you MUST implement robust security measures
// to prevent abuse and unauthorized access.  This example is NOT secure
// as-is and should only be used for learning and testing.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param PDO $pdo The database connection object.
 *
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo)
{
    // 1. Validate inputs (VERY IMPORTANT in a real application)
    if (empty($email) || empty($newPassword)) {
        return false; // Or throw an exception
    }
    // Add more validation here, such as:
    // - Email format validation
    // - Password strength validation (minimum length, special characters, etc.)

    // 2. Check if the user exists
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // User not found
    }

    // 3. Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // 4. Update the user's password in the database
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedNewPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        return false; // Update failed
    }

    return true;
}


// Example Usage (Demonstration - Replace with your actual code)

// Database connection (Replace with your actual credentials)
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_database_user';
$password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);

    // Enable error reporting (for development only - disable in production)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "
";
    exit;
}


// Simulate a forgotten password request
$email = 'testuser@example.com'; // Replace with a valid user email
$newPassword = 'NewSecretPassword123!';

if (forgotPassword($email, $newPassword, $pdo)) {
    echo "Password reset successfully for $email
";
} else {
    echo "Password reset failed for $email
";
}

// Close the database connection (optional, but good practice)
$pdo = null;

?>
