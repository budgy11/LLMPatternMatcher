

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email) {
    // 1. Check if the email exists in the users table
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error); // Log the error for debugging
        return false;
    }

    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $user_email = $user['email'];
    $stmt->close();

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random token

    // 3. Prepare the password reset query
    $query = "UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $token, time() + 3600, $user_id); // Token, expiry (1 hour), user ID

    if ($stmt->execute()) {
        $stmt->close();
        // 4. Send the password reset link (implementation omitted for brevity - see below)
        // Implement your email sending logic here.
        // Example:  Send an email with a link like:
        //  http://yourwebsite.com/reset_password.php?token=$token&user_id=$user_id
        return true;
    } else {
        $stmt->close();
        error_log("Failed to update user password reset token: " . $conn->error); // Log the error
        return false;
    }
}


// Example Usage (This would be triggered by a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (!empty($email)) {
        if (forgot_password($email)) {
            echo "<p>Password reset email has been sent to $email.</p>";
        } else {
            echo "<p>Failed to generate password reset link. Please try again later.</p>";
        }
    } else {
        echo "<p>Please enter your email address.</p>";
    }
}
?>
