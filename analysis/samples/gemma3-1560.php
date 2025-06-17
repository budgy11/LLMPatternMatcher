    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was generated and sent, false otherwise.
 */
function forgotPassword($email)
{
    // 1. Sanitize and Validate Input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        error_log("Error: Email address is empty."); // Log for debugging.  Don't display to user.
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Error: Invalid email format."); // Log for debugging
        return false;
    }

    // 2. Check if user exists
    $user = getUserByEmail($email);  // Assumes you have a getUserByEmail() function

    if (!$user) {
        error_log("Error: User not found with email: $email");  // Log
        return false;
    }


    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a secure random token

    // 4.  Store the Token and User ID in a Temporary Table (for security)
    //    This prevents a direct lookup of the user's ID from the database
    //    and protects against brute-force attacks.
    $mysqli = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if ($mysqli->connect_errno) {
        error_log("Failed to connect to database: " . $mysqli->connect_error);
        return false;
    }

    $sql = "INSERT INTO password_reset_tokens (user_id, token, expires_at)
            VALUES ($user['id'], '$token', NOW() + INTERVAL 1 HOUR)";  //  Token expires after 1 hour
    if (!$mysqli->query($sql)) {
        error_log("Error creating password reset token: " . $mysqli->error);
        $mysqli->close();
        return false;
    }
    $mysqli->close();

    // 5.  Send the Reset Link (Email)
    $resetLink = $GLOBALS['app_url'] . "/reset-password?token=$token&user_id=$user['id']";  //  Include user_id for security
    $subject = "Password Reset Link";
    $message = "Please click the following link to reset your password: " . $resetLink;
    $headers = "From: " . $GLOBALS['app_email'] . "\r
";
    mail($email, $message, $headers); // Or use a more robust email sending library.



    return true; // Reset link generated and sent
}



/**
 * Example of a function to get a user by their email.
 *  Replace with your actual database query logic.
 *
 * @param string $email The email address of the user.
 * @return array|null An array containing user data if found, or null if not found.
 */
function getUserByEmail($email) {
  // Replace this with your database query.  This is just an example.
  //  Consider using prepared statements for security.
  $result = mysqli_query($GLOBALS['db'], "SELECT * FROM users WHERE email = '$email'");  // Replace with your query
  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    return $user;
  } else {
    return null;
  }
}



// Example Usage (for testing - DON'T USE IN PRODUCTION without proper error handling and security)
// You would normally call this from a form submission.
// $email = $_POST['email'];
// if (isset($email)) {
//   if (forgotPassword($email)) {
//     echo "Password reset email has been sent to $email.";
//   } else {
//     echo "Error: Failed to generate password reset link.";
//   }
// }
?>
