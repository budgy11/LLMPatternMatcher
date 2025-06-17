

<?php

/**
 * Forgets a user's password.
 *
 * This function sends an email to the user's registered email address
 * containing a temporary password.  It also logs the reset attempt.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL for the password reset link.  
 *                         Should include the domain and path (e.g., 'https://example.com/reset_password').
 * @param string $salt The salt used for hashing the password.  Important for security.
 *
 * @return bool True on success, false on failure (e.g., email not sent).
 */
function forgotPassword(string $email, string $baseUrl, string $salt)
{
    // Validate email format (basic check - improve this for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error
        return false;
    }

    // Generate a temporary password
    $temporaryPassword = generateTemporaryPassword(6); // Adjust length as needed

    // Hash the temporary password
    $hashedTemporaryPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT, ['salt' => $salt]);

    // Store the temporary password and user ID in the database
    $userId = getUserIDByEmail($email); //  Assume you have a function to get the user ID
    if (!$userId) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // Store the temporary password and timestamp in the database.  Consider using a dedicated
    // table for reset tokens to avoid collisions.
    $resetToken = password_hash($temporaryPassword . '_' . $userId, PASSWORD_DEFAULT, ['salt' => $salt]); // Add userId to token
    
    // Store data in database (replace with your actual database interaction)
    // Example:
    // $sql = "INSERT INTO password_resets (user_id, token, created_at) VALUES ($userId, '$resetToken', NOW())";
    // mysqli_query($connection, $sql);  // Replace $connection with your database connection

    // Send the password reset email
    if (!sendResetPasswordEmail($email, $temporaryPassword, $baseUrl)) {
        error_log("Failed to send password reset email to " . $email);
        // Consider handling this differently depending on your requirements.
        // You might try sending the email again later, or return an error.
        return false;
    }

    return true;
}

/**
 * Generates a random temporary password.
 * 
 * @param int $length The desired length of the password.
 * @return string The generated temporary password.
 */
function generateTemporaryPassword(int $length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|\'":<>?/';
    $password = '';
    $passwordLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $char = $characters[rand(0, $charLength)];
        $password .= $char;
    }
    return $password;
}


/**
 * Placeholder function for sending the password reset email.
 * 
 * @param string $email The email address of the user.
 * @param string $temporaryPassword The temporary password.
 * @param string $baseUrl The base URL for the password reset link.
 * @return bool True on success, false on failure.
 */
function sendResetPasswordEmail(string $email, string $temporaryPassword, string $baseUrl) {
    // Implement your email sending logic here using a library like PHPMailer.
    // This is a placeholder.

    $subject = "Password Reset Link";
    $message = "Please click the following link to reset your password: " . $baseUrl . "/reset_password?token=" . urlencode($temporaryPassword); 

    //  In a real implementation, you would use a mail library
    //  to send the email.  For example:
    //  $mail = new PHPMailer\PHPMailer\PHPMailer();
    //  $mail->SetSMTPDebugEnable(true);
    //  $mail->addAddress($email, 'User');
    //  $mail->Subject = $subject;
    //  $mail->Body = $message;
    //  $mail->send();

    return true; // Assume success for this example
}


/**
 * Placeholder function for getting the user ID by email.
 * 
 * @param string $email The email address of the user.
 * @return int|null The user ID if found, null otherwise.
 */
function getUserIDByEmail(string $email) {
  // Implement your logic to retrieve the user ID from the database
  // based on the email address. This is a placeholder.
  // Example:
  // $result = mysqli_query($connection, "SELECT id FROM users WHERE email = '$email'");
  // if ($result) {
  //   $row = mysqli_fetch_assoc($result);
  //   return $row['id'];
  // } else {
  //   return null;
  // }
    return null; // Placeholder
}

?>
