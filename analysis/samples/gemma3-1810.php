
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

// This is a simplified example.  In a production environment,
// you *absolutely* should implement proper security measures:
// - Use strong passwords
// - Generate and verify unique tokens
// - Handle email sending securely (using SMTP or a trusted service)
// - Implement rate limiting to prevent brute-force attacks
// - Store passwords securely (hashed and salted)


// Simulate a user database (replace with your actual database connection)
$users = [
  'john.doe@example.com' => ['password' => 'securePassword123', 'salt' => 'someSalt'],
  'jane.smith@example.com' => ['password' => 'anotherPassword', 'salt' => 'anotherSalt'],
];

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
    // 1. Check if the email exists in the user database.
    if (!isset($users[$email])) {
        return false; // User not found
    }

    // 2. Generate a unique token (for security, don't use a simple counter)
    $token = bin2hex(random_bytes(32)); // Generate a strong random token

    // 3. Store the token and expiration time in the database
    //    (This is just a placeholder - you'd use a proper database query)
    //  In a real application, you'd update the 'token' and 'expires_at' fields in the database.
    //  For this example, we'll just add it to the $users array.
    $users[$email]['token'] = $token;
    $users[$email]['expires_at'] = time() + 3600; // Token expires in 1 hour


    // 4. Send an email with a reset link
    //  -  Include the token and a link to the reset password page
    $subject = 'Password Reset Request';
    $message = "Please click on the following link to reset your password: " .
               '<a href="' . $_SERVER['PHP_SELF'] . '?token=' . $token . '&email=' . $email . '" >Reset Password</a>'; //  Use absolute URL
    $headers = 'From: admin@example.com' . "\r
";
    mail($email, $message, $headers);

    return true;
}


// Example usage (for testing - this is NOT a front-end form)
if (isset($_GET['token']) && isset($_GET['email'])) {
  $token = $_GET['token'];
  $email = $_GET['email'];

  if (isset($users[$email]['token']) && $users[$email]['token'] == $token) {
    //  Password reset logic would go here.  You'd actually redirect
    //  to a page where the user can enter a new password.
    echo "Token is valid.  Redirection to reset password page would occur here.";
  } else {
    echo "Invalid token or email.";
  }
}

?>
