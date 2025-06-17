

<?php

// Assuming you have a database connection established (e.g., $pdo)
// and a table named 'users' with a 'email' column.

/**
 * Resets a user's password via email.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // Validate email format (basic validation - consider more robust validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log for debugging
        return false;
    }

    // Generate a unique, secure token (e.g., using a random string)
    $token = bin2hex(random_bytes(32));  // Best practice: use bin2hex for security.

    // Hash the token - very important for security.
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);  // Use password_hash

    //  Prepare the SQL query
    $pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace with your details
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // Prepare and execute the query to update the user's record
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry, reset_sent = 1 WHERE email = :email");

        // Bind the parameters
        $stmt->bindParam(':token', $hashed_token);
        $stmt->bindParam(':expiry', time() + 3600);  // Token expires after 1 hour (adjust as needed)
        $stmt->bindParam(':email', $email);

        $result = $stmt->execute();

        if ($result) {
            // Send the password reset email
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . $token;
            $headers = "From: your_email@example.com" . "\r
";

            if (mail($email, $subject, $message, $headers)) {
                return true;
            } else {
                // Log the email sending failure
                error_log("Failed to send email to " . $email);
                return false;
            }
        } else {
            // Log the query failure
            error_log("Query failed to update user: " . $email);
            return false;
        }
    } catch (PDOException $e) {
        // Log the database error
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}


// Example Usage (This is just for demonstration - use this within your form handling)
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
    }
}
*/

?>
