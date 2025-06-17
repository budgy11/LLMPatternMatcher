
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password($email)
{
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided."); //Log for debugging
        return false;
    }

    // 1. Generate a unique, time-based token.  This is CRUCIAL for security.
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

    // 2. Hash the token.
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 3. Store the hashed token in the database, associated with the user's email.
    $query = "INSERT INTO password_resets (email, token, created_at) 
              VALUES ('$email', '$hashed_token', NOW())";

    if (!$result = mysqli_query($GLOBALS['db_host'], $query)) {
        error_log("Error inserting password reset token: " . mysqli_error($GLOBALS['db_host']));  //Log error for debugging
        return false;
    }
    
    // 4. Send an email to the user containing a link with the token.
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "Please click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com"; // Replace with your sender email

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Error sending email for password reset.");  // Log for debugging
        return false;
    }
}


// Example Usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (empty($email)) {
        echo "Error: Please enter your email address.";
    } else {
        if (forgot_password($email)) {
            echo "Password reset link has been sent to your email address.";
        } else {
            echo "Error: Failed to initiate password reset.";
        }
    }
}
?>
