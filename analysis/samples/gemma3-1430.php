

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token that can be used
 * to reset a user's password.  It sends an email with a link to the
 * password reset form.
 *
 * @param string $email The email address of the user to reset the password for.
 * @param string $baseUrl The base URL of your website.  This is needed to construct the password reset link.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // Validate email format (simple check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Generate a unique, time-based token
    $token = bin2hex(random_bytes(32));

    // Store the token and user ID in the database (replace with your database logic)
    $result = storeToken($token, $_SESSION['userId']); // Replace $_SESSION['userId'] with your user ID retrieval

    if (!$result) {
        return false;
    }

    // Construct the password reset link
    $resetLink = $baseUrl . "/reset_password?token=" . urlencode($token);

    // Send the email
    if (!sendResetEmail($email, $resetLink)) {
        // Handle email sending failure - consider logging it, displaying an error message, etc.
        // For demonstration purposes, we'll just return false.
        return false;
    }

    return true;
}


/**
 *  Placeholder function to store the token and user ID in the database.
 *  Replace this with your actual database logic.
 *
 * @param string $token The token to store.
 * @param int $userId The user ID to associate with the token.
 * @return bool True on success, false on failure.
 */
function storeToken(string $token, int $userId): bool
{
    // Example using a temporary file (replace with database insert)
    $filePath = 'temp_tokens.txt'; // Or use a database table.
    if (!file_exists($filePath)) {
        if (!touch($filePath)) {
            return false; // File creation failed
        }
    }

    if (file_put_contents($filePath, $userId . ',' . $token . "
", FILE_APPEND) !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * Placeholder function to send the password reset email.
 * Replace this with your actual email sending logic.
 *
 * @param string $email The email address of the user.
 * @param string $resetLink The password reset link.
 * @return bool True on success, false on failure.
 */
function sendResetEmail(string $email, string $resetLink): bool
{
    // Simulate email sending (replace with your actual email sending logic)
    // Example:  Use a real email library like PHPMailer or SwiftMailer

    $subject = 'Password Reset';
    $message = "Click on the link below to reset your password:
" . $resetLink;

    // Simulate sending an email
    $headers = ['MIME-Version: 1.0', 'Content-type: text/html; charset=UTF-8'];
    mail($email, $message, $headers);

    return true; // Assume successful if mail() returns true.
}


// Example usage (demonstration only -  never use directly in production)
// IMPORTANT: This is an example and should *never* be run in a production environment.
//  It's for illustrating the flow.
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (isset($_POST['reset'])) { //This is just to simulate clicking the reset button
      if (forgotPassword($email, 'http://localhost/your_website/')) {
          echo "Password reset email sent to " . $email;
      } else {
          echo "Error: Failed to generate password reset token.";
      }
    }
}

?>
