
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
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with columns 'email' and 'password'

/**
 * Resets a user's password via email.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, PDO $db) {
    // Sanitize the email input (important for security!)
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email); // Log for debugging
        return false;
    }

    // Check if the user exists
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("User not found for email: " . $email); // Log for debugging
        return false;
    }

    // Generate a unique, secure token (e.g., using a cryptographically secure random number generator)
    $token = bin2hex(random_bytes(32));  // Generates a 32-byte (256-bit) random string
    // Or use:  $token = password_generate(uniqid()); // if you're using password_generate

    // Prepare the token setting statement
    $stmt = $db->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiry', time() + 3600, PDO::PARAM_INT); // Token expires in 1 hour
    $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);
    $result = $stmt->execute();


    if ($result) {
        // Send the password reset email
        $subject = "Password Reset Request";
        $to = $user['email'];
        $headers = "From: your_email@example.com"; // Replace with your email
        $message = "Please click the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . $token; // Replace with your domain

        mail($to, $subject, $message, $headers);

        return true;
    } else {
        error_log("Failed to update user with token: " . $email);
        return false;
    }
}

// Example Usage (in your controller or script):
// $email = $_POST['email'];  // Get email from form submission

// if (isset($email)) {
//     if (forgot_password($email, $db)) {
//         echo "Password reset email sent to " . $email;
//     } else {
//         echo "Failed to send password reset email.";
//     }
// }
?>
