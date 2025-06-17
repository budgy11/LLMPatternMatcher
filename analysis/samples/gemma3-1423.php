

<?php

// Assuming you have a database connection established
// This is a simplified example, adapt to your database structure
// and security practices.

// Configuration (Change these to your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    return "Database connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, password, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $hashedPassword = $user['password']; //  Important:  Store hashed passwords
    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Generate a secure random token
    // 4. Update the user record with the token (add to password column or create a separate 'tokens' table)
    $update_query = "UPDATE users SET token = '$token' WHERE id = '$userId'";
    if (!$conn->query($update_query)) {
      return "Error updating user data.";
    }

    // 5. Send the password reset email
    $to = $email;
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password.php?token=$token"; // Use HTTPS if possible
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);

    return "Password reset link sent to your email.  Check your inbox!";
  } else {
    return "User with this email address not found.";
  }

  $conn->close();
}


// Example Usage (for testing -  This will not work directly without a form)
//  This demonstrates how you would call the function.
/*
$email = "test@example.com"; // Replace with the user's email
$resetMessage = forgot_password($email);
echo $resetMessage;
*/


// **IMPORTANT SECURITY NOTES & BEST PRACTICES**

// 1. **Hashing Passwords:**  NEVER store passwords in plain text.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.  The example uses `$user['password']`, which represents the *hashed* password.

// 2. **Token Expiration:** Implement an expiration time for the password reset token.  This prevents attackers from using the token after it has expired. You can store the expiration time in the database (e.g., a 'token_expiry' column).

// 3. **Secure Token Generation:** Use `random_bytes()` to generate cryptographically secure random tokens. `bin2hex()` converts the bytes into a hexadecimal string, making it suitable for URL parameters.

// 4. **HTTPS:** ALWAYS use HTTPS to protect the password reset link and the user's email address.

// 5. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.

// 6. **Input Validation:** Validate all user inputs (email format, token, etc.).

// 7. **Error Handling:** Provide informative error messages to the user.

// 8. **Security Audits:** Regularly review your code for security vulnerabilities.

// 9. **Separate Tables (Recommended):** For improved security and organization, consider using separate tables for users and tokens. This isolates the tokens, making it harder for attackers to compromise the password reset process.

// 10. **Email Verification:** Send a verification email to the user to confirm they received the reset link.

// 11. **Don't Reveal Sensitive Information in Error Messages:**  Avoid revealing database details or other sensitive information in error messages that might be exposed to users.


<?php

// Include the database connection file
require_once 'config.php'; // Replace 'config.php' with your actual database configuration file.

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password($email)
{
    // Validate email format (basic check - improve as needed for your application)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error for debugging
        return false;
    }

    // Check if the user exists
    $user = mysqli_query($conn, "SELECT id, email FROM users WHERE email = '$email'");

    if (mysqli_num_rows($user) == 0) {
        error_log("User with email '$email' not found."); // Log the error
        return false;
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
    
    // Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // Update the user's record with the token and timestamp
    $update_query = "UPDATE users SET reset_token = '$hashed_token', reset_timestamp = NOW() WHERE email = '$email'";
    $result = mysqli_query($conn, $update_query);

    if (mysqli_error($conn)) {
        error_log("Error updating user record: " . mysqli_error($conn));
        return false;
    }

    // Send an email with the reset link
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://www.yourwebsite.com/reset_password.php?token=$token"; //  Replace with your actual URL
    $headers = "From: yourname@yourwebsite.com";  //Replace with your actual email address.

    mail($to, $message, $headers);

    return true;
}

// Example Usage (For testing - outside of a request handler)
// $email_to_reset = "test@example.com"; // Replace with a valid email
// if (forgot_password($email_to_reset)) {
//     echo "Password reset email sent to " . $email_to_reset;
// } else {
//     echo "Failed to reset password.";
// }

?>
