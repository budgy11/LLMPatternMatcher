
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assume you have a database connection established (e.g., $db)
// and a user table with an 'email' and 'password' column.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset link was generated and sent, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email (Important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email);  // Log for debugging
    return false;
  }


  // 2. Check if the user exists
  $user = getUserByEmail($email);  // Assuming you have a function to fetch user data
  if (!$user) {
    error_log("User with email " . $email . " not found."); // Log for debugging
    return false;
  }

  // 3. Generate a unique token (using a secure method)
  $token = generateUniqueToken();

  // 4. Store the token and expiry date in the database
  //  (Important:  Don't store the *password* itself in the database!)
  $result = storeToken($user['id'], $token, time() + (120 * 60)); // Token expiry: 2 hours (120 minutes)

  if (!$result) {
    error_log("Failed to store token for user " . $email);
    return false;
  }



  // 5. Generate the reset link
  $resetLink = generateResetLink($user['email'], $token);

  // 6. Send the reset link (e.g., via email)
  if (!sendEmail($user['email'], "Password Reset", $resetLink)) {
    // Handle email sending failure (e.g., retry, log error)
    error_log("Failed to send password reset email to " . $email);
    // You might want to delete the token if email sending fails.  For example:
    // deleteToken($user['id'], $token);
    return false;
  }


  return true;
}


// --- Helper Functions (Implement these based on your database and email setup) ---

/**
 * Fetches a user's data by email.
 *
 * @param string $email The user's email address.
 * @return array|null An array containing user data, or null if not found.
 */
function getUserByEmail(string $email) {
  // Replace this with your database query
  // This is just a placeholder
  $userData = [
    'id' => 123, // Example user ID
    'email' => $email,
    // Add other user fields here if needed
  ];
  return $userData;
}



/**
 * Generates a unique token.
 * Use a more robust method than a simple timestamp for security.
 *  Consider using a library for generating cryptographically secure random strings.
 *
 * @return string A unique token.
 */
function generateUniqueToken() {
  return bin2hex(random_bytes(32)); // Example:  Use random_bytes for more secure randomness
}


/**
 * Stores the token and expiry date in the database.
 *
 * @param int $userId The user's ID.
 * @param string $token The token.
 * @param int $expiryTime The expiry time (Unix timestamp).
 * @return bool True if the token was stored successfully, false otherwise.
 */
function storeToken(int $userId, string $token, int $expiryTime) {
  // Replace this with your database update query
  // Example:
  $sql = "UPDATE users SET password_reset_token = '$token', password_reset_expiry = '$expiryTime' WHERE id = $userId";
  // Example using mysqli
  // $result = mysqli_query($db, $sql);

  // Or with PDO:
  // $stmt = $db->prepare($sql);
  // $stmt->execute();

  return true; //  Replace with your actual database result check
}

/**
 * Deletes a token from the database.  Useful if email sending fails.
 *
 * @param int $userId The user's ID.
 * @param string $token The token.
 * @return bool True if the token was deleted successfully, false otherwise.
 */
function deleteToken(int $userId, string $token) {
   // Replace this with your database query
  // Example using mysqli
  // $sql = "DELETE FROM users WHERE id = $userId AND password_reset_token = '$token'";
  // $result = mysqli_query($db, $sql);

    // Or with PDO:
   // $stmt = $db->prepare($sql);
   // $stmt->execute();

   return true;  // Replace with your actual database result check
}


/**
 * Generates the password reset link.
 *
 * @param string $email The user's email address.
 * @param string $token The token.
 * @return string The generated reset link.
 */
function generateResetLink(string $email, string $token) {
  return "https://example.com/reset-password?token=" . $token . "&email=" . $email;
}



/**
 * Sends the password reset email.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $body The email body.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendEmail(string $to, string $subject, string $body) {
  // Replace this with your email sending logic (e.g., using PHPMailer)
  // This is just a placeholder
  // Example using PHPMailer (you'll need to install it: composer require phpmailer/phpmailer)
  // $mail = new PHPMailer(true);
  // $mail->SetFrom('noreply@example.com', 'Example Website');
  // $mail->AddAddress($to);
  // $mail->Subject = $subject;
  // $mail->Body = $body;
  // $mail->IsSMTP();
  // $mail->SMTPDebugEnable(true);
  // $mail->Send();
  // return true;

  // Placeholder:  Return false for now to simulate failure
  return false;
}



// --- Example Usage ---
// $email = 'user@example.com';
// if (forgotPassword($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to generate password reset link.";
// }
?>
