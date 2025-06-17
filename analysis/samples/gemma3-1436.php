        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


// Example:  Using password_hash()
$password = $_POST['password'];  // Get the user's new password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Then, in your query:
$sql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";


<?php

/**
 * Resets a user's password.  This is a basic example and should be
 * thoroughly secured in a production environment.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password to set.
 * @param PDO $pdo The PDO database connection object.
 * @return bool True if password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo)
{
    // Input validation - very important!
    if (empty($email) || empty($newPassword)) {
        error_log("Forgot password function: Missing email or password."); // Log for debugging
        return false;
    }

    // Check if the email exists in the user table
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        error_log("Forgot password function: User with email $email not found.");
        return false;
    }

    // Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedNewPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        error_log("Forgot password function: Failed to update password for user ID $user['id']");
        return false;
    }

    return true;
}

// Example Usage (Illustrative - Replace with your actual database setup)
// -------------------------------------------------------------------
// Assume you have a PDO database connection object called $pdo
// -------------------------------------------------------------------

// Example Database Setup (Replace with your actual database details)
$host = 'localhost';
$dbName = 'your_database_name';
$username = 'your_db_username';
$password = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
// -------------------------------------------------------------------


// Example Usage
$email = 'testuser@example.com'; // Replace with a valid user email
$newPassword = 'NewSecurePassword123'; // Replace with the desired new password

if (forgotPassword($email, $newPassword, $pdo)) {
    echo "Password reset successful.  Check your email to confirm (if you've enabled email confirmation)."
} else {
    echo "Password reset failed. Please try again.";
}

?>
