

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with a 'email' column.

/**
 * Forgets a user's password by sending a password reset link to their email.
 *
 * @param string $email The email address of the user.
 * @return bool True if the reset link was successfully generated and emailed, 
 *              false otherwise (e.g., user not found or email sending failed).
 */
function forgotPassword(string $email)
{
    // 1. Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log the error for debugging
        return false;
    }

    // 2. Fetch the user from the database
    $stmt = $db->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        error_log("User with email $email not found."); // Log the error
        return false;
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // 3. Generate a unique, time-based token
    $token = bin2hex(random_bytes(32)); // Create a secure random token
    // Alternative: Use a library like Hashids for human-readable tokens.

    // 4. Hash the token (for security) -  Important!
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    // 5. Store the token in the database, associated with the user
    $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
    $stmt->bind_param("ssi", $hashedToken, time() + 3600, $userId);  // Token expiry after 1 hour
    $result = $stmt->execute();

    if (!$result) {
        error_log("Error updating user's reset token: " . print_r($stmt->error, true)); // Log the error
        return false;
    }

    // 6. Email the user with the reset link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>" . $_SERVER['PHP_SELF'] . "?reset_token=$token</a>"; // Construct the link (using PHP_SELF for correct URL)
    $headers = "From: Your Website <yourwebsite@example.com>";

    if (mail($email, $subject, $message, $headers)) {
        // Success!
        return true;
    } else {
        error_log("Failed to send email to $email."); // Log the email sending failure
        // Consider adding an error handling mechanism here, like a form to re-send.
        return false;
    }
}


// Example Usage (for testing - remove in production)
//  This example is for illustration purposes.  Do not expose this directly in a live web application.

// Simulate a database connection (replace with your actual connection)
// For demonstration, we'll use an empty object.

class MockDB {
    public function prepare(string $query) {
        // Return a mock result for demonstration.
        return new MockResult();
    }
    public function execute(string $query) {
        return new MockResult();
    }
}

class MockResult {
    public function get_result() {
        // Mocking a user with email 'test@example.com'
        return new MockUser();
    }
}

class MockUser {
    public function fetch_assoc() {
        return [
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com'
        ];
    }
}


if (isset($_GET['reset_token'])) {
    $resetToken = $_GET['reset_token'];

    // Reset Password Logic (Illustrative, this is NOT production code)
    // (Replace this with your actual password reset processing)
    if (forgotPassword($resetToken)) {
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Error sending password reset link. Please try again later.";
    }
}
?>
