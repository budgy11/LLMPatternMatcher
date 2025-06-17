  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email)
{
    // 1. Check if the email exists
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error); // Log the error for debugging
        return false;
    }

    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter

    if (!$stmt->execute()) {
        error_log("Query execution failed: " . $stmt->error); // Log the error
        $stmt->close();
        $conn->close();
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        error_log("User with email $email not found."); // Log the user not found
        $stmt->close();
        $conn->close();
        return false;
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $user_email = $user['email'];

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random string

    // 3. Store the token and user ID in the database (using a temporary table - recommended for security)
    $conn->query("CREATE TEMPORARY TABLE IF NOT EXISTS reset_tokens (user_id INT, token VARCHAR(32))");
    $stmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $token);
    if (!$stmt->execute()) {
        error_log("Error inserting into reset_tokens: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
    $stmt->close();


    // 4. Send the password reset email
    $to = $user_email;
    $subject = "Password Reset";
    $message = "Please click the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password?token=" . $token; // Replace yourdomain.com with your actual domain
    $headers = "From: your_email@example.com";  // Replace with your sender email

    mail($to, $message, $headers); //  Uses PHP's mail function.  Consider using a more robust email sending library.


    // 5. Close the connection
    $conn->close();

    return true;
}

// Example usage (for testing - DON'T use this in production without proper validation!)
// $email = "test@example.com"; // Replace with a valid email address to test.
// if (forgot_password($email)) {
//     echo "Password reset email sent to $email.";
// } else {
//     echo "Password reset failed.";
// }

?>
