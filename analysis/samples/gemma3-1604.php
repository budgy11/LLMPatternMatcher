

<?php

/**
 * Forgot Password Function
 *
 * This function allows users to reset their passwords.
 * It generates a unique token, sends an email with a reset link, and
 * allows the user to confirm the password reset.
 *
 * @param string $email The email address of the user.
 * @return bool True if the password reset process was initiated successfully,
 *              false otherwise.
 */
function forgot_password(string $email)
{
    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random bytes

    // 2.  Store the token and user ID in the database (example - adjust as needed)
    //  This is just an example.  You'll likely use your own database connection
    //  and query logic.

    // Assuming you have a database connection called $db
    // $db->query("DELETE FROM password_resets WHERE email = '$email'");  // Optional - clear old resets
    $user_id = getUserIDbyEmail($email);  // Helper function to get user ID
    if ($user_id === false) {
        return false; // User not found.
    }


    $db->query("INSERT INTO password_resets (user_id, token, expiry) VALUES ($user_id, '$token', NOW() + INTERVAL 1 HOUR)");


    // 3. Generate the reset link
    $reset_link = urlencode($db->query("SELECT token FROM password_resets WHERE user_id = $user_id LIMIT 1")->fetch_assoc()['token']);


    // 4. Send the email
    $subject = "Password Reset Link";
    $headers = "From: your_website@example.com\r
"; // Replace with your email address
    $message = "Click on the following link to reset your password: " . $reset_link;

    $sent = mail($email, $subject, $message, $headers);


    // 5. Return true if the email was sent
    return $sent;
}


/**
 * Helper function to get user ID by email.  Replace with your database logic.
 *
 * @param string $email The email address to search for.
 * @return int|false The user ID if found, false otherwise.
 */
function getUserIDbyEmail(string $email): ?int
{
    //  Replace this with your database query to retrieve the user ID by email.
    // Example using MySQLi:
    // $result = $db->query("SELECT id FROM users WHERE email = '$email'");
    // if ($result->num_rows > 0) {
    //   return $result->fetch_assoc()['id'];
    // } else {
    //   return false;
    // }

    // Placeholder -  Always return false for now
    return false;

}


// Example Usage (Demonstration only - Replace with your actual input)
// $email = "testuser@example.com"; // Replace with a real email

// if (forgot_password($email)) {
//   echo "Password reset email has been sent to $email.";
// } else {
//   echo "Failed to initiate password reset for $email.";
// }

?>
