

<?php

// IMPORTANT:  This is a simplified example for demonstration purposes only.
// In a production environment, you MUST:
// 1.  Store passwords securely (hashed and salted).
// 2.  Implement robust security measures against brute-force attacks.
// 3.  Use a proper email sending library to avoid spam and ensure delivery.
// 4.  Never log or store email reset tokens.
// 5.  Consider adding CAPTCHA or other verification steps.

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets the user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error);  // Log the error
        return false;
    }

    // 2. Check if the user exists
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();


    // 3. Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32)); //  A strong, random token

    // 4. Store the token in the database associated with the user
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $resetToken, $resetToken); // i = integer, s = string
    $stmt->execute();
    $stmt->close();



    // 5. Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click the following link to reset your password: " .  $_SERVER['PHP_SELF'] . "?reset_token=" . $resetToken; //Use the current script name to generate a direct link.
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    // 6.  Close the connection
    $conn->close();

    return true;
}


// Example Usage (This would typically be in a form submission handler)
if (isset($_GET['reset_token'])) {
    $resetToken = $_GET['reset_token'];

    if (forgot_password($resetToken)) {
        echo "Password reset email sent to " . $resetToken . ".  Check your inbox.";
    } else {
        echo "Error: Could not reset password.";
    }
}
?>
