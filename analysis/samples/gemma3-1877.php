

<?php

// Assuming you have a database connection established and stored in a variable like $db

/**
 * Forgets the password for a user.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Input (Crucial for Security)
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided."); // Log the error
        return false;
    }

    // 2.  Check if the user exists
    $user = get_user_by_email($email); // Implement this function (see below)
    if (!$user) {
        error_log("Forgot Password: User with email {$email} not found.");
        return false;
    }

    // 3. Generate a Unique Token (Securely)
    $token = generate_unique_token(); // Implement this function (see below)

    // 4.  Store Token in Database (Associating with User)
    //    This is where you'd update the 'token' column in your user table.
    update_user_token($user['id'], $token); // Implement this function (see below)

    // 5.  Send Password Reset Email
    //    You'll need to format and send an email with a link to the reset page
    //    The email link should include the token.
    send_password_reset_email($user['email'], $token); // Implement this function (see below)

    return true;
}



/**
 *  Helper functions (Implement these based on your database schema)
 */

/**
 *  Retrieves a user's data by email.  This is just an example.
 *  Replace with your actual database query.
 *
 *  @param string $email
 *  @return array|null An associative array containing user data if found, null otherwise.
 */
function get_user_by_email(string $email): ?array
{
    // Replace this with your database query to get user data by email
    // Example (using MySQLi) -  Adapt to your database system
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return null;
    }
}



/**
 * Generates a unique token. Use a cryptographically secure random number generator.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32));  // More secure than rand()
}



/**
 * Updates the 'token' column in the user's record with the given token.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 */
function update_user_token(int $userId, string $token): void
{
    // Replace this with your database query to update the 'token' column.
    // Example (using MySQLi)
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET token = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $token, $userId);
        if ($stmt->execute()) {
            $stmt->close();
        } else {
            error_log("Error updating token for user ID {$userId}: " . $conn->error);
        }
    } else {
        error_log("Error preparing update statement: " . $conn->error);
    }

    $conn->close();
}



/**
 * Sends an email with a password reset link.
 *
 * @param string $email The email address of the user.
 * @param string $token The token associated with the password reset request.
 */
function send_password_reset_email(string $email, string $token): void
{
    // Implement the logic to send the email. This is a placeholder.
    // Use a reliable email sending service (e.g., SendGrid, Mailgun, AWS SES)
    // or configure your own SMTP server.

    //Example of what the email content might look like:
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=" . urlencode($token) . "'>Reset Password</a>";
    //  Replace this with your actual email sending function
    //  For example:
    //  mail($email, $subject, $message);

    error_log("Password reset email sent to {$email} with token {$token}");
}


// Example Usage (IMPORTANT:  Use this only in testing.  Never in production!)
//  To test, you'd call this from a form submission (e.g., using POST)
//  and the reset_token would be passed from the form to the server.
//  Uncomment the code below for testing ONLY.
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (forgot_password($email)) {
        echo "Password reset email sent to {$email}.";
    } else {
        echo "Failed to initiate password reset.";
    }
}
*/

?>
