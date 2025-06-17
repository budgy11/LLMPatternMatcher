
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password based on their email address.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);  //  Implement this function (see example below)
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a Unique Token and Store it in the Database
    $token = generate_unique_token();
    $result = $db->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    if ($result === false) {
        error_log("Error updating user's reset token: " . $db->error);
        return false;
    }

    // 4. Send the Password Reset Email
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>Reset Password</a>";
    $headers = "From: your_email@example.com";  // Replace with your email
    $sent = send_email($email, $subject, $message, $headers);


    if (!$sent) {
        // Handle email sending failure (e.g., log the error, try again later)
        error_log("Failed to send password reset email to " . $email);
        //Optionally, you could try to manually trigger a retry.
        return false;
    }

    return true;
}


/**
 * Example function to retrieve user data by email (Replace with your actual database query)
 * This is a placeholder and needs to be adapted to your database schema.
 *
 * @param string $email The email address to search for.
 * @return array|null An associative array representing the user data, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}


/**
 * Generates a unique token for password resets.  Should be cryptographically secure.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Uses a cryptographically secure random number generator.
}



// Example Usage (Demonstration - Don't use in production without proper validation)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
    }
}
?>
