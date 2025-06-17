
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

        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required><br><br>

        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset email was sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Check if the email exists
    $user = getUserByEmail($email);

    if (!$user) {
        return false; // User not found
    }

    // 2. Generate a unique token
    $token = generateUniqueToken();

    // 3. Store the token and user ID in the database
    $result = storeTokenForUser($user['id'], $token);

    if (!$result) {
        return false; // Failed to store token
    }

    // 4. Send the reset email
    $subject = "Password Reset Request";
    $message = "Click on the following link to reset your password: " . base_url() . "/reset-password?token=" . $token;  //  base_url() assumes you have this defined globally or in a config file

    $headers = ["Content-Type: text/html"]; //  Use HTML for better display

    $sent = sendEmail($email, $subject, $message, $headers);

    if (!$sent) {
        // Optionally, you can delete the token if the email fails to send
        deleteTokenForUser($user['id']);
        return false;
    }

    return true;
}


/**
 * Helper function to get user by email.  Replace this with your actual database query.
 *
 * @param string $email The email address to search for.
 * @return array|null  The user object if found, null otherwise.
 */
function getUserByEmail(string $email): ?array
{
    // *** REPLACE THIS WITH YOUR DATABASE QUERY ***
    // This is just a placeholder example.  You MUST adapt this to your database.
    // Example using a dummy database array:
    $users = [
        ['id' => 1, 'email' => 'user1@example.com', 'password' => 'hashed_password'],
        ['id' => 2, 'email' => 'user2@example.com', 'password' => 'hashed_password'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Stronger token
}


/**
 * Stores a token for a user.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if the token was stored successfully, false otherwise.
 */
function storeTokenForUser(int $userId, string $token): bool
{
    // *** REPLACE THIS WITH YOUR DATABASE INSERTION ***
    // Example using a dummy database insertion:
    $db = getDatabaseConnection();  //  Assume this function returns a database connection object

    $sql = "INSERT INTO password_tokens (user_id, token, expiry_date) VALUES (?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $userId, $token);

    return $stmt->execute();
}

/**
 * Deletes a token for a user.
 *
 * @param int $userId The ID of the user.
 * @return bool True if the token was deleted successfully, false otherwise.
 */
function deleteTokenForUser(int $userId): bool
{
    // *** REPLACE THIS WITH YOUR DATABASE DELETE ***
    // Example using a dummy database deletion:
    $db = getDatabaseConnection();

    $sql = "DELETE FROM password_tokens WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);

    return $stmt->execute();
}


/**
 * Sends an email.  Replace this with your email sending mechanism.
 *
 * @param string $to        The email address to send to.
 * @param string $subject   The email subject.
 * @param string $message   The email body.
 * @param array  $headers   Email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendEmail(string $to, string $subject, string $message, array $headers = [])
{
    // *** REPLACE THIS WITH YOUR EMAIL SENDING LOGIC ***
    // Example using a dummy email sending function:
    //  This is just a placeholder. Implement your actual email sending.
    //   You would likely use a library like PHPMailer.
    //echo "Sending email to: " . $to . "
Subject: " . $subject . "
Message: " . $message . "
";
    return true; // Replace with your actual email sending result
}


/**
 * Placeholder function to get a database connection.
 * @return  Database Connection object
 */
function getDatabaseConnection(): object {
  //  Replace this with your database connection code
  //  This is a dummy connection, adapt to your database.
  return new stdClass(); //return a dummy object to avoid errors
}

?>
