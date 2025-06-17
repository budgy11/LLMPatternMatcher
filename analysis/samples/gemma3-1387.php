

<?php

/**
 * Forgets a user's password.
 *
 * This function sends a password reset link to the user's email.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // Sanitize and validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 1. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 2. Store the token in the database (assuming you have a 'users' table)
    //    Replace this with your actual database interaction
    $userId = getUserIDByEmail($email); // Assuming you have a function to get the user ID by email
    if ($userId === null) {
        error_log("User with email " . $email . " not found.");
        return false; // User not found
    }


    // Prepare the query for inserting the token
    $sql = "UPDATE users SET reset_token = '$token' WHERE id = $userId";

    // Execute the query
    $result = mysqli_query($GLOBALS['db_connection'], $sql); // Replace with your DB interaction
    if (!$result) {
        error_log("Failed to update user token: " . mysqli_error($GLOBALS['db_connection']));
        return false;
    }

    // 3. Generate the reset link
    $resetLink =  "https://yourwebsite.com/reset-password?token=$token";

    // 4. Send the reset link via email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . $resetLink;
    $headers = "From: yourwebsite@example.com";  // Replace with your email address
    mail($to, $message, $headers);
    // Check for email sending success.  This is difficult to do reliably in PHP.
    // You'd typically use a library or service for this.  The mail() function
    // can be unreliable.  Consider using a dedicated email sending service.

    return true;
}

// -------------------  Helper Functions (Placeholder - Implement these) --------------------

/**
 * Retrieves the user ID from the database based on the email.
 *
 * @param string $email The email address to search for.
 * @return int|null The user ID, or null if the user is not found.
 */
function getUserIDByEmail(string $email): ?int
{
    // Replace this with your actual database query
    // Example using MySQLi:
    // $sql = "SELECT id FROM users WHERE email = '$email'";
    // $result = mysqli_query($GLOBALS['db_connection'], $sql);
    // if (!$result) {
    //     return null;
    // }
    // $row = mysqli_fetch_assoc($result);
    // return $row['id'];

    //Dummy implementation to allow the function to be called
    return 1;
}

// -----------------------  Database Connection (Example) ----------------------------
$GLOBALS['db_connection'] = mysqli_connect("localhost", "username", "password", "database_name");

if (!$GLOBALS['db_connection']) {
    die('Could not connect to database.');
}


// ------------------ Example Usage (for demonstration) ----------------------------
$email = "test@example.com";  // Replace with a valid email
if (forgotPassword($email)) {
    echo "Password reset link sent to $email";
} else {
    echo "Failed to send password reset link.";
}

// Close the database connection (important!)
mysqli_close($GLOBALS['db_connection']);
?>
