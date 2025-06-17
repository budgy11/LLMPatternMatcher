

<?php
// ... (include config.php) ...

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password($email)
{
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email);
        return false;
    }

    // Check if the user exists
    $user = mysqli_query($conn, "SELECT id, email FROM users WHERE email = '$email'");

    if (mysqli_num_rows($user) == 0) {
        error_log("User with email '$email' not found.");
        return false;
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    // Hash the token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // Update the user's record
    $update_query = "UPDATE users SET reset_token = ? , reset_timestamp = NOW() WHERE email = ?";
    $result = mysqli_query($conn, $update_query, $hashed_token, $email); //Pass parameters to prepared statement

    if ($result === false) {
        error_log("Error updating user record: " . mysqli_error($conn));
        return false;
    }

    // Send an email with the reset link
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://www.yourwebsite.com/reset_password.php?token=$token";
    $headers = "From: yourname@yourwebsite.com";

    mail($to, $message, $headers);

    return true;
}
?>
