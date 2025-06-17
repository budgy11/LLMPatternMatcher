

<?php

// Configuration - Replace with your actual database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASSWORD', 'your_db_password');
define('DB_NAME', 'your_db_name');

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgot_password(string $email)
{
    // 1. Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        // Handle connection error
        error_log("Database connection failed: " . $conn->connect_error); // Log the error for debugging
        return false;
    }

    // 2.  Check if the email exists in the 'users' table
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // "s" indicates a string parameter

    if (!$stmt->execute()) {
        // Handle execution error
        error_log("Query execution failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }

    $result = $stmt->get_result();

    if (!$result) {
        // Handle result error
        error_log("Failed to get result from query");
        $stmt->close();
        $conn->close();
        return false;
    }

    if ($result->num_rows == 0) {
        // Email not found
        $stmt->close();
        $conn->close();
        return false;
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $resetToken = generate_unique_token(); // Generate a unique token for the reset
    $resetTime = time(); //  Store the timestamp for expiry

    // 3. Generate a unique token (e.g., using a random string)
    function generate_unique_token() {
        return bin2hex(random_bytes(32)); // Generate a 32-byte random string
    }

    // 4. Create a temporary table to store the reset token data
    $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS reset_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            token VARCHAR(255) NOT NULL,
            expiry_time INT UNSIGNED NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    if (!$conn->query($sql)) {
        // Handle query error
        error_log("Failed to create temporary table: " . $conn->error);
        $stmt->close();
        $conn->close();
        return false;
    }



    // 5. Insert the token data into the temporary table
    $sql = "INSERT INTO reset_tokens (user_id, token, expiry_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $resetToken, $resetTime);

    if (!$stmt->execute()) {
        // Handle execution error
        error_log("Failed to insert into reset_tokens table: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }


    // 6.  Email the user with the reset link
    $to = $email;
    $subject = 'Password Reset Link';
    $message = "Please click this link to reset your password: " .  $_SERVER['REQUEST_SCHEME'] . "://". $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $resetToken; //Use the request scheme for secure link
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($to, $subject, $message, $headers)) {
        // Email sent successfully
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // Email sending failed
        error_log("Failed to send email");
        $stmt->close();
        $conn->close();
        return false;
    }
}


// Example Usage (For demonstration purposes - handle this carefully in a real application)
// Note:  This is just a placeholder.  You'll need to integrate this with your
//       authentication process and a proper form/validation mechanism.
// This example does *NOT* handle security best practices like preventing CSRF
// or sanitizing input.

// Simulate a request
// if (isset($_GET['email'])) {
//   $email = $_GET['email'];
//   if (forgot_password($email)) {
//       echo "Password reset email sent to " . $email;
//   } else {
//       echo "Error resetting password for " . $email;
//   }
// }


?>
