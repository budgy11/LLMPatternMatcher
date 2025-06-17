

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token,
 * sends an email with a link to reset the password,
 * and then deletes the token from the database.
 *
 * @param string $email The user's email address.
 * @param string $db_host Your database host.
 * @param string $db_name Your database name.
 * @param string $db_username Your database username.
 * @param string $db_password Your database password.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $db_host, string $db_name, string $db_username, string $db_password) {
    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a secure random number generator

    // 2. Prepare the SQL query
    $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, NOW())";

    // 3. Prepare the statement
    $stmt = db_connect($db_host, $db_name, $db_username, $db_password);

    // 4. Execute the query
    if ($stmt) {
        if ($stmt->execute([$email, $token])) {
            // 5. Send the password reset email (implementation omitted for brevity)
            //    You should replace this with your actual email sending logic.
            //    This is a placeholder to demonstrate the process.
            send_password_reset_email($email, $token); 

            // 6. Clear the statement
            $stmt->close();
            return true;
        } else {
            // Handle database error
            error_log("Error executing password reset query: " . print_last_error());
            $stmt->close();
            return false;
        }
    } else {
        // Handle database connection error
        error_log("Error connecting to database: " . print_last_error());
        return false;
    }
}


/**
 * Placeholder function for sending the password reset email.  
 * You MUST implement your own email sending logic here.
 *
 * @param string $email The user's email address.
 * @param string $token The generated token.
 */
function send_password_reset_email(string $email, string $token) {
    // Replace this with your email sending implementation.
    // This is just a placeholder.
    error_log("Password reset email sent to: " . $email . " with token: " . $token);
}


/**
 *  Placeholder function for connecting to the database.
 *  Replace this with your actual database connection logic.
 *
 * @param string $host
 * @param string $db_name
 * @param string $db_username
 * @param string $db_password
 * @return mysqli|false
 */
function db_connect(string $host, string $db_name, string $db_username, string $db_password) {
  $conn = new mysqli($host, $db_username, $db_password, $db_name);
  return $conn;
}

// Example Usage:
// Replace with your database credentials and user email
$db_host = "localhost";
$db_name = "your_database_name";
$db_username = "your_username";
$db_password = "your_password";

$user_email = "test@example.com";

if (forgot_password($user_email, $db_host, $db_name, $db_username, $db_password)) {
    echo "Password reset email has been sent to $user_email. Check your inbox!";
} else {
    echo "Failed to generate password reset token.";
}

?>
