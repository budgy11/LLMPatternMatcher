

<?php

// This is a simplified example.  In a production environment,
// you *must* implement more robust security measures.
// This includes:
// 1. Using a secure password reset token system (e.g., UUIDs)
// 2. Validating email addresses
// 3. Rate limiting to prevent abuse
// 4. Logging all reset requests for audit purposes
// 5. Hashing passwords securely (never store plain text passwords)

// Assuming you have a database connection established as $db

/**
 *  Forgot Password Function
 *
 *  This function handles the process of sending a password reset link
 *  to the user's email address.
 *
 *  @param string $email The email address of the user requesting a password reset.
 *
 *  @return bool True if the reset link was generated successfully, false otherwise.
 */
function forgot_password($email)
{
    // 1. Validate Email (Basic - Improve this in a production environment)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error for debugging
        return false;
    }

    // 2. Check if user exists
    $user = getUserByEmail($email); // Assume this function exists and returns user data or null
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique reset token
    $reset_token = generate_unique_token();  // Assume this function generates a UUID or similar

    // 4. Store the token in the database associated with the user's ID
    //    (This is a simplified example - use prepared statements to prevent SQL injection!)
    //  Example using prepared statements is below (more secure)
    $query = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $reset_token, date("Y-m-d H:i:s", time() + 3600), $email); // Token expiry (1 hour)
    $result = $stmt->execute();
    $stmt->close();

    if (!$result) {
        error_log("Error updating user reset token: " . $db->error);
        return false;
    }

    // 5. Send the reset link to the user's email address
    $subject = "Password Reset Link";
    $message = "Please click the following link to reset your password: " .  app_url . "/reset_password?token=" . $reset_token;
    $headers = "From: " . app_name . " <" . app_email . ">\r
";

    if (send_email($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email);
        // Consider deleting the token from the database if email sending fails
        // This prevents the token from being used indefinitely.
        delete_reset_token($user['id'], $reset_token);
        return false;
    }
}

/**
 *  Helper functions (simplified - adapt to your database and email sending logic)
 */

function getUserByEmail($email) {
  // Replace this with your actual database query to fetch the user
  // based on the email address.  Use prepared statements!

  // Example using a placeholder - adjust for your database structure
  // return $db->query("SELECT * FROM users WHERE email = '$email'")->fetch_assoc();
  return null;
}

function generate_unique_token() {
  // Replace with a robust UUID generation library
  return bin2hex(random_bytes(32)); // Simple example, use a proper UUID library
}

function send_email($to, $subject, $message, $headers) {
  // Implement your email sending logic here.  Use a reliable email library.
  // Example using PHPMailer:
  //  require_once 'PHPMailer/PHPMailerAutoload.php';
  //  $mail = new PHPMailer();
  //  $mail->addAddress($to, $email);
  //  $mail->setFrom($from, $from);
  //  $mail->Subject = $subject;
  //  $mail->Body = $message;
  //  $mail->AltBody = strip_tags($message);
  //  if ($mail->send()) {
  //    return true;
  //  } else {
  //    return false;
  //  }
  return false; // Placeholder
}

//Example usage:
// $email = "test@example.com";
// if (forgot_password($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to generate password reset link.";
// }

?>
