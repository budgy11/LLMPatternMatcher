
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// IMPORTANT:  This is a simplified example for demonstration purposes.
// In a production environment, you MUST implement robust security measures
// like using a strong password hashing algorithm (bcrypt, Argon2) and
// sending reset links securely (HTTPS, token expiration, rate limiting).

class User {
    private $username;
    private $password; // In a real app, store *hashed* passwords
    private $email;

    public function __construct($username, $password, $email) {
        $this->username = $username;
        $this->password = $password; // Placeholder for hashing
        $this->email = $email;
    }
}

class PasswordReset {
    private $user;
    private $resetToken;

    public function __construct(User $user, $resetToken) {
        $this->user = $user;
        $this->resetToken = $resetToken;
    }
}

// Example Usage (Simulated)
// 1. User Requests Password Reset
// Assume a form submission sends the username
$username = $_POST['username'];

// 2. Validate User
$users = [
    ['username' => 'john.doe', 'password' => 'hashed_password_here', 'email' => 'john.doe@example.com'],
    ['username' => 'jane.smith', 'password' => 'hashed_password_here', 'email' => 'jane.smith@example.com'],
];

$user = null;
foreach ($users as $u) {
    if ($u['username'] === $username) {
        $user = new User($u['username'], $u['password'], $u['email']);
        break;
    }
}

if ($user === null) {
    echo "User not found.";
    exit;
}


// 3. Generate a Unique Reset Token
$resetToken = bin2hex(random_bytes(32));  // Generate a secure random token
// Store this token in your database associated with the user.
// Example (Simplified - Replace with your database interaction)
//  $db->insert('password_resets', ['user_id' => $user->getId(), 'token' => $resetToken, 'expiry' => time() + 3600]); // Store for 1 hour

// 4. Send Reset Link (Simulated)
$resetLink = "http://example.com/reset-password?token=$resetToken";
echo "A password reset link has been sent to $user->email: $resetLink<br>";

// 5.  (In a real implementation, you would handle the link click,
//      validate the token, and allow the user to set a new password.)


//  Example Reset Link Handling (This is illustrative - adapt for your logic)
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 6. Validate Token (Crucial - Don't skip this!)
    // In a production system, you'd check if the token exists in your database,
    // if the token is valid, and if it hasn't expired.
    // Example:
    // $reset = $db->get('password_resets', ['token' => $token]);
    // if ($reset && $reset['expiry'] > time()) {
    //     // Token is valid, proceed with password reset.
    // } else {
    //     echo "Invalid or expired reset token.";
    //     exit;
    // }
}

?>
