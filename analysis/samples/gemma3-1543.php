
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgot_password($email)
{
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error); // Log the error for debugging
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 3. Prepare the SQL query to insert the token into the 'password_reset_tokens' table
    $sql = "INSERT INTO password_reset_tokens (user_email, reset_token, expiry_timestamp)
            VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sii", $email, $token, time() + 3600); // Token, email, expiration timestamp (1 hour)

    // Execute the query
    if (!$stmt->execute()) {
        error_log("Query failed: " . $stmt->error); // Log the error
        return false;
    }

    // 4.  Create a link to the reset password page
    $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

    // 5. Return the reset link.
    return $reset_link;
}


/**
 * Example Usage (for testing/development - *DO NOT* include this directly in your website)
 */
// Example of calling the function:
// $reset_link = forgot_password('user@example.com');

// if ($reset_link) {
//     echo "Password reset link: " . $reset_link . "<br>";
// } else {
//     echo "Failed to generate password reset link.";
// }

// Stop the script execution if running as a script to avoid outputting the example
// This is crucial for security - preventing direct access to the example code.
// You would typically only enable this in a development environment.
//  echo "<br>Stop Execution <br>";
// exit;
?>
