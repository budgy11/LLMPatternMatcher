

<?php

// This is a simplified example and should be used with caution in a production environment.
// It's highly recommended to use a more robust and secure solution like a dedicated password reset service.

// --- Configuration (IMPORTANT:  Replace with your actual database settings!) ---
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// ---  Helper functions (Improve security and readability) ---
function sanitize_input($data) {
  // Basic sanitation - enhance as needed for your application
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function send_password_reset_email($email, $token) {
    //  Replace this with your actual email sending logic
    //  This example just prints the email to the console.

    // You'll need to implement a proper email sending mechanism here.
    // This often involves using a library or API.

    // Example:
    $subject = 'Password Reset - Your Account';
    $message = "Click this link to reset your password: " . '<a href="?reset_token=' . $token . '" target="_blank">Reset Password</a>';
    $headers = 'From: your_email@example.com' . "\r
";

    // In a real application, you'd use:
    // mail($email, $subject, $message, $headers);

    echo "Simulated email sent to: " . $email . " with token: " . $token . "
";
}



/**
 * Forgot Password Function
 *
 * This function handles the forgot password request.
 *
 * @param string $email The user's email address.
 * @return bool True if the process initiated successfully, false otherwise.
 */
function forgot_password($email) {
    // 1. Validate Email (Basic)
    $email = sanitize_input($email);
    if (empty($email)) {
        return false; // Invalid email
    }

    // 2. Check if user exists
    $query = "SELECT id, password_hash, email FROM users WHERE email = '$email'";
    $result = mysqli_query($GLOBALS['db_host'], $query); // Use mysqli_query and pass the database host.

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            // User exists, generate a token and send a reset email
            $reset_token = bin2hex(random_bytes(32)); // Generate a secure, random token
            $query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";
            $update_result = mysqli_query($GLOBALS['db_host'], $query);

            if ($update_result) {
                send_password_reset_email($email, $reset_token);
                return true; // Process initiated successfully
            } else {
                // Error updating the database. Log this!
                error_log("Error updating user: " . mysqli_error($GLOBALS['db_host']));
                return false;
            }

        } else {
            // User not found
            return false;
        }
    } else {
        // Database query error
        error_log("Database error: " . mysqli_error($GLOBALS['db_host']));
        return false;
    }
}



// --- Example Usage (For testing - DO NOT USE DIRECTLY IN A PRODUCTION APPLICATION) ---
//  Remember to replace with a real email sending mechanism.
if (isset($_POST['forgot_email'])) {
    $email = sanitize_input($_POST['forgot_email']); // Sanitize the input before use.

    if (forgot_password($email)) {
        echo "Password reset email sent to " . $email . ".  Check your inbox!";
    } else {
        echo "An error occurred while processing your request.";
    }
}
?>
