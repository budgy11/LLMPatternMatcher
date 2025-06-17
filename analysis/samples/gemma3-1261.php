

<?php

/**
 * Forgets a user's password by sending a password reset link.
 *
 * @param string $email The user's email address.
 * @param string $password_reset_token A unique token for the reset link.
 * @param string $secret_key The secret key used to generate the token.  This should be kept secret.
 * @param PDO $pdo The database connection object.
 *
 * @return bool True if the reset link was successfully sent, false otherwise.
 */
function forgot_password(string $email, string $password_reset_token, string $secret_key, PDO $pdo): bool
{
    // 1. Generate a random token
    $token = generate_password_reset_token($secret_key, $pdo); // Use helper function for token generation

    // 2. Insert the token into the database for the user.
    $sql = "INSERT INTO password_resets (user_email, password_reset_token, expiry_timestamp)
            VALUES (:email, :token, :expiry)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry_timestamp', time() + 3600);  // Token expires in 1 hour (3600 seconds)
        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        // Handle database errors - Log or display a user-friendly message
        error_log("Error creating password reset link: " . $e->getMessage());
        return false;
    }
}


/**
 * Helper function to generate a password reset token.
 *
 * @param string $secret_key The secret key.
 * @param PDO $pdo The database connection object.
 *
 * @return string A unique token.
 */
function generate_password_reset_token(string $secret_key, PDO $pdo): string
{
    $token = bin2hex(random_bytes(32));  // Generate a 32-byte (256-bit) random token
    $sql = "SELECT token FROM password_resets WHERE token = :token";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Token already exists - generate a new one
            return generate_password_reset_token($secret_key, $pdo);
        }
        return $token;

    } catch (PDOException $e) {
        error_log("Error generating password reset token: " . $e->getMessage());
        return ''; // Or throw an exception, depending on your error handling strategy
    }
}


// Example usage (Illustrative -  Replace with your database setup and security practices)
//
// $pdo = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
//
// $email = "test@example.com"; // Replace with the user's email
// $token = generate_password_reset_token("your_super_secret_key", $pdo);
//
// if ($token) {
//     if (forgot_password($email, $token, "your_super_secret_key", $pdo)) {
//         echo "Password reset email sent to " . $email . " with a link expiring in 1 hour.";
//     } else {
//         echo "Failed to create password reset link.";
//     }
// } else {
//     echo "Failed to generate password reset token.";
// }

?>
