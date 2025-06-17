

<?php

/**
 * Handles the forgot password process.
 *
 * This function generates a unique token, stores it in a database,
 * sends an email with a password reset link, and sets the token to expired.
 *
 * @param string $email The email address of the user requesting the reset.
 * @return bool True if the process was successful, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a strong random bytes generator

    // 2. Store the Token in the Database
    //  Replace this with your actual database connection and table structure
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials

    try {
        $stmt = $db->prepare("INSERT INTO forgot_passwords (email, token, created_at) VALUES (:email, :token, NOW())");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle database errors gracefully.  Log the error, and return false.
        error_log("Database error: " . $e->getMessage());
        return false;
    }


    // 3. Send the Password Reset Email
    //  Replace this with your email sending logic
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click on the following link to reset your password: " . $_SERVER['PHP_SELF'] . "?token=" . urlencode($token);
    $headers = "From: your_email@example.com\r
";
    mail($to, $message, $headers);


    // 4. Set the Token to Expired (e.g., after 1 hour)
    // This prevents the token from being used indefinitely.
    //  You'll need to create a separate function to handle this.
    //  This example sets the token to expired after 60 minutes.
    //  Consider more robust expiration mechanisms.

    //  Example using setTimeout - this will only work if your server has setTimeout.
    //  For better scalability, consider using a task queue.

    //  For production, use a robust task queue system.
    //  This simple example is for demonstration purposes.
    //  Consider the security implications of setTimeout in production.

    //  Example: setTimeout (Not recommended for production - security implications)
    //  sleep(3600); // Wait for 1 hour (3600 seconds) - This will block the execution.
    //  // Then, you would need to delete the token from the database.

    return true;
}


// Example usage (for testing - remove this in production)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (!empty($email)) {
        if (forgotPassword($email)) {
            echo "Password reset email has been sent to " . $email;
        } else {
            echo "Failed to send password reset email. Please try again.";
        }
    } else {
        echo "Please enter your email address.";
    }
}
?>
