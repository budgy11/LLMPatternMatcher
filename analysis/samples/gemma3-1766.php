
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

/**
 * Forgets a user's password.
 *
 * This function generates a unique, random token and sends an email to the user
 * with instructions on how to reset their password.
 *
 * @param string $email The user's email address.
 * @param string $baseUrl The base URL of your website (e.g., 'https://www.example.com').
 * @return bool True if an email was sent, false otherwise.
 */
function forgot_password(string $email, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));

    // 3. Store the Token and User ID (IN A SECURE WAY!)
    //  IMPORTANT:  Never store passwords directly.  Store the *hashed* password,
    //  and this token provides a mechanism to let the user reset it.
    $userId = get_user_id_by_email($email); // Assumed function - implement this
    if ($userId === null) {
        error_log("User not found for email: " . $email); // Log for debugging
        return false;
    }


    // 4. Prepare the Reset URL
    $resetUrl = $baseUrl . "/reset-password?token=" . urlencode($token);

    // 5. Prepare the Email Message
    $subject = "Password Reset Request";
    $message = "Dear User,

" .
               "You have requested to reset your password.

" .
               "Please click on the following link to reset your password:
" .
               $resetUrl . "

" .
               "If you did not request this password reset, please ignore this email.";

    // 6. Send the Email
    $headers = "From: " . "noreply@" . getenv('DOMAIN') . "\r
"; // Use your domain
    $result = mail($email, $subject, $message, $headers);


    // 7. Return True if Email Sent, False Otherwise
    return $result === true;
}


/**
 *  Placeholder function - Replace with your actual implementation.
 *  This function should retrieve the user ID associated with the email.
 *
 * @param string $email The user's email address.
 * @return int|null The user ID, or null if not found.
 */
function get_user_id_by_email(string $email): ?int
{
    //  Replace this with your database query or whatever mechanism you use
    //  to get the user ID based on the email.  This is just a placeholder.
    //  Example:
    //  $db = new PDO(...);
    //  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    //  $stmt->execute([$email]);
    //  $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //  return $row['id'] ?? null;


    // Example placeholder (returns a dummy value for demonstration)
    return 123;
}


// Example Usage (Replace with your actual email address and website URL)
$email = "testuser@example.com";
$baseUrl = "https://www.example.com";

if (forgot_password($email, $baseUrl)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email.
";
}
?>
