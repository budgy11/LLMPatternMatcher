

<?php

// IMPORTANT:  This is a basic example and should NOT be used in a production environment.
// It's designed for demonstration and educational purposes.  A real-world implementation
// needs robust security measures, rate limiting, email verification, and more.

// 1. Database connection (replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// 2. Create a connection
$conn = new mysqli($host, $username, $password, $database);

// 3. Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function reset_password(string $email): bool {
    // Sanitize the email address (important for security)
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Get the user's ID based on the email
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // User not found
    }

    $user_id = $result->fetch_assoc()['id'];
    $stmt->close();

    // Generate a unique, random password reset token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator.

    // Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // Update the user's record with the new token
    $sql = "UPDATE users SET reset_token = ? , reset_token_expiry = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, time(), $user_id); // "s" for string, time() for expiry
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Send an email with the reset link
        $to = $email;
        $subject = 'Password Reset';
        $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a> to reset your password.";
        $headers = "From: your_email@example.com" . "\r
";  // Replace with your email address

        mail($to, $subject, $message, $headers);

        return true;
    } else {
        return false; // Update failed
    }
}



// Example Usage (for demonstration only - don't use directly in a public-facing application)
if (isset($_GET['reset'])) {
    $token = $_GET['reset'];
    if (reset_password($token)) {
        echo "Password reset email sent. Check your inbox.";
    } else {
        echo "Failed to reset password.";
    }
}

// 4. Close the connection (important for resource management)
$conn->close();

?>
