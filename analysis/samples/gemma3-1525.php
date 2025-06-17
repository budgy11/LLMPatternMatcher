
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// This is just a basic example - production code needs more robust security
// and error handling.

// Function to reset the password
function forgotPassword($email) {
    // 1. Check if the email exists in the user table
    $user = getUserByEmail($email);

    if (!$user) {
        return false; // Email not found
    }

    // 2. Generate a unique, temporary password token
    $token = generateUniqueToken();

    // 3. Store the token and user ID in the database (temporary table)
    //    This is for demonstration purposes.  In a real app, you'd likely 
    //    use a separate table for password resets, potentially with a 
    //    time limit.
    $result = saveResetToken($user['id'], $token);
    if (!$result) {
        return false; // Failed to save token
    }

    // 4. Send an email to the user with a password reset link
    $resetLink = generateResetLink($token, $user['email']);
    sendResetPasswordEmail($user['email'], $resetLink);

    return true; // Password reset link sent successfully
}


// Helper functions (implement these based on your database and email setup)

/**
 * Retrieves a user from the database based on their email address.
 *
 * @param string $email The email address to search for.
 * @return array|null  An array containing user data if found, or null if not.
 */
function getUserByEmail($email) {
    // Replace with your database query.  This is a placeholder!
    // Example (MySQL):
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($db, $query);
    // if (mysqli_num_rows($result) > 0) {
    //     $user = mysqli_fetch_assoc($result);
    //     return $user;
    // }
    // return null;

    // Placeholder:  Simulating a user
    $users = [
        ['id' => 1, 'email' => 'test@example.com']
    ];

    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Generates a unique token.  Use a cryptographically secure method for production.
 *
 * @return string A unique token.
 */
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Uses random_bytes for better security
}


/**
 * Saves the token and user ID to the database.
 *
 * @param int $userId The user ID.
 * @param string $token The token.
 * @return bool True if successful, false otherwise.
 */
function saveResetToken($userId, $token) {
    // Replace with your database query.
    // Example (MySQL):
    // $query = "INSERT INTO password_resets (user_id, token, created_at) VALUES ('$userId', '$token', NOW())";
    // $result = mysqli_query($db, $query);
    // return $result;

    // Placeholder:  Simulating successful save
    return true;
}



/**
 * Generates a reset link with the token.
 *
 * @param string $token The token.
 * @param string $email The user's email address.
 * @return string The reset link.
 */
function generateResetLink($token, $email) {
    return "/reset_password?token=$token&email=$email"; // Adjust URL accordingly
}


/**
 * Sends an email with the reset link.  Replace with your email sending logic.
 *
 * @param string $to       The recipient's email address.
 * @param string $resetLink The reset link.
 */
function sendResetPasswordEmail($to, $resetLink) {
    // Implement your email sending logic here (e.g., using PHPMailer or similar)
    // This is just a placeholder.
    echo "Sending reset password email to: " . $to . " with link: " . $resetLink;
}



// Example Usage (Demonstration)
// Note:  This part is for testing.  Don't expose this directly in a production environment.
//  Sanitize and validate any user input *before* calling the function.


//  Simulate a password reset request
$email = 'test@example.com';

if (forgotPassword($email)) {
    echo "Password reset link sent to $email. Check your email!";
} else {
    echo "Failed to generate password reset link for $email.";
}


?>
