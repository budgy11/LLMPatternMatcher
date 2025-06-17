

<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// $db = new mysqli($host, $username, $password, $database);
// Check connection
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }


/**
 * Forgets the user's password by sending a password reset link to their email.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided.");
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email); // Assuming you have a getUserByEmail function
    if ($user === false) {
        error_log("User with email '$email' not found.");
        return false;
    }

    // 3. Generate a Unique Reset Token
    $resetToken = generateUniqueToken();

    // 4. Store the Token (Important for Security)
    //  -  This is where you'd typically hash and salt the token.
    //  -  Storing the raw token in a database is generally discouraged due to security risks.
    //  -  Hashing and salting the token is crucial for security.
    //  -  This example keeps it simple, but **DO NOT USE THIS IN PRODUCTION!**
    $isValid = saveResetToken($user->id, $resetToken);
    if (!$isValid) {
        error_log("Failed to save reset token.");
        return false;
    }


    // 5. Create the Reset Link (Email Content)
    $resetLink = createResetLink($user->email, $resetToken);

    // 6. Send the Email
    if (!sendResetEmail($user->email, $resetLink)) {
        error_log("Failed to send reset email.");
        // Optionally, you could delete the token from the database
        // if you want to invalidate it if the email fails to send.
        // deleteResetToken($user->id, $resetToken);
        return false;
    }

    return true;
}

// ---------------------------------------------------------------------
// Placeholder functions - Replace with your actual implementation
// ---------------------------------------------------------------------

/**
 *  Placeholder function to get a user by their email.  Replace with your database query.
 * @param string $email
 * @return mysqli_result|false
 */
function getUserByEmail(string $email): false
{
    // **Replace this with your database query to fetch the user**
    // Example using mysqli:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = $db->query($query);
    // if ($result->num_rows > 0) {
    //     $user = $result->fetch_assoc();
    //     return $user;
    // }
    // return false;

    // Mock data for testing
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'anotherpass']
    ];
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return false;
}


/**
 *  Placeholder function to generate a unique token.
 * @return string
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}


/**
 * Placeholder function to save the reset token to the database.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function saveResetToken(int $userId, string $token): bool
{
    // **Replace this with your database query to save the token**
    // Example using mysqli:
    // $query = "INSERT INTO reset_tokens (user_id, token, expiry_date) 
    //           VALUES ($userId, '$token', NOW() + INTERVAL 1 HOUR)";
    // $result = $db->query($query);
    // return $result;

    // Mock implementation for testing - does nothing
    return true;
}


/**
 * Placeholder function to delete the reset token from the database.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function deleteResetToken(int $userId, string $token): bool {
    // **Replace this with your database query to delete the token**
    // Example using mysqli:
    // $query = "DELETE FROM reset_tokens WHERE user_id = $userId AND token = '$token'";
    // $result = $db->query($query);
    // return $result;

    // Mock implementation for testing - does nothing
    return true;
}


/**
 * Placeholder function to create the reset link (email content).
 * @param string $email
 * @param string $token
 * @return string
 */
function createResetLink(string $email, string $token): string
{
    return "<a href='reset_password.php?token=$token'>Reset Password</a>";
}

/**
 * Placeholder function to send the reset email.
 * @param string $email
 * @param string $resetLink
 * @return bool
 */
function sendResetEmail(string $email, string $resetLink): bool
{
    // **Replace this with your email sending logic**
    // Example using PHPMailer:
    // require_once 'PHPMailer/PHPMailerAutoload.php';
    // $mail = new PHPMailer();
    // $mail->SMTPDebugEnable = false; // Set to true for debugging
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->Username = 'your_smtp_username';
    // $mail->Password = 'your_smtp_password';
    // $mail->SetFrom('your_email@example.com', 'Your Website Name');
    // $mail->Subject = 'Password Reset';
    // $mail->Body = $resetLink;
    // $mail->AddAddress($email);
    // return $mail->send();

    // Mock implementation for testing - does nothing
    return true;
}

// ---------------------------------------------------------------------
// Example Usage (For Testing)
// ---------------------------------------------------------------------

// To test, you'd call this function from a form submission:
// $email = $_POST['email'];
// $result = forgotPassword($email);
// if ($result) {
//    echo "Password reset link sent to $email.";
// } else {
//    echo "Failed to send password reset link.";
// }

?>
