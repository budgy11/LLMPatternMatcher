
<!-- HTML Form to Trigger the Password Reset -->
<form method="post" action="">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

/**
 * Forgot Password Function
 *
 * This function allows a user to reset their password.
 *
 * **Important Security Considerations:**
 * - **Never store passwords in plain text.** Always use a strong hashing algorithm (e.g., bcrypt, Argon2id) with a salt.
 * - **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.
 * - **Email Verification:** Strongly consider adding email verification to the reset link to ensure it's actually going to the intended user.
 * - **Session Management:**  Use secure and proper session management.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True on success, false on failure.  Prints error messages to the browser if needed.
 */
function forgot_password($email)
{
    // Sanitize the email address to prevent injection vulnerabilities
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email address.</p>";
        return false;
    }

    // Check if the user exists in the database
    $user = get_user_by_email($email); // Replace with your database query

    if ($user === null) {
        echo "<p style='color:red;'>User not found.</p>";
        return false;
    }

    // Generate a unique token
    $token = generate_unique_token();  // Replace with your token generation function

    // Store the token associated with the user's email.  (Use a database or session)
    // Example using a database:
    // $result = mysqli_query($conn, "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($user_id, '$token', NOW() + INTERVAL 1 HOUR)");

    // Example using sessions (less secure for long-term storage):
    $_SESSION['reset_token'] = $token;
    $_SESSION['reset_user_id'] = $user['id']; // Store user ID for security
    $_SESSION['reset_expiry'] = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

    // Send an email with the reset link
    $subject = 'Password Reset';
    $to = $email;
    $reset_link = generate_reset_link($token); // Generate the reset link
    $message = "Click this link to reset your password: " . $reset_link;
    $headers = 'From: your_website@example.com' . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        echo "<p style='color:green;'>Password reset email sent to $email.</p>";
        return true;
    } else {
        echo "<p style='color:red;'>Failed to send password reset email.</p>";
        return false;
    }
}


/**
 * Placeholder functions - Replace with your actual implementations.
 */

/**
 * Placeholder to get a user by their email.
 *
 * @param string $email The email address.
 * @return array|null An array containing user data, or null if not found.
 */
function get_user_by_email($email)
{
    // Replace this with your database query to retrieve the user.
    // Example using mysqli:
    // $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    // If the query returns a row, you'll typically fetch it using mysqli_fetch_assoc() or similar.
    // If no row is found, mysqli_fetch_assoc() returns false.

    // For demonstration, returning a dummy user:
    $dummy_user = [
        'id' => 123,
        'email' => 'test@example.com',
        'password' => 'hashed_password'  // Remember to hash passwords!
    ];
    return $dummy_user;
}


/**
 * Placeholder to generate a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token()
{
    return bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator
}


/**
 * Placeholder to generate a reset link.
 *
 * @param string $token The token.
 * @return string The reset link.
 */
function generate_reset_link($token)
{
    return 'http://yourwebsite.com/reset-password?token=' . $token;
}

?>
