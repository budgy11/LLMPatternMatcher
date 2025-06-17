    <label for="email">Enter your email address:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

/**
 * Resets a user's password after a forgotten password request.
 *
 * This function generates a unique token, sends an email with a reset link,
 * and stores the token in the database for verification.
 *
 * @param string $email The email address of the user requesting the reset.
 * @return bool True if the reset process was initiated successfully, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Secure random token (32 bytes)

  // 2. Hash the Token (Important for security!)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 3. Store the Token in the Database
  //  - Replace this with your actual database connection and query.
  //  - This is a simplified example.
  $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");  // Adjust credentials
  try {
    $stmt = $db_connection->prepare(
      "INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())"
    );
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':token', $hashed_token);
    $stmt->execute();
  } catch (PDOException $e) {
    // Handle database errors - log them, show a user-friendly message, etc.
    error_log("Database error in forgot_password: " . $e->getMessage());
    return false;
  }

  // 4. Send the Reset Email (Implement your email sending logic here)
  $reset_link = "http://yourwebsite.com/reset_password?token=" . $token; // Replace with your domain
  $subject = "Password Reset Request";
  $message = "Click on the link below to reset your password:
" . $reset_link;
  $headers = "From: your_email@example.com\r
";
  // Use a proper email sending function (e.g., PHPMailer) here instead of relying on simple echo
  // This example just prints the message for demonstration.  Do not use this in production.

  //print_r($headers . $message);
  // Example:
  // send_email($email, $subject, $message, $headers);

  return true;
}

// Example Usage (For testing - do not use directly in your application!)
// $user_email = "test@example.com";
// if (forgot_password($user_email)) {
//   echo "Password reset link sent to " . $user_email;
// } else {
//   echo "Failed to initiate password reset.";
// }

// ---  Example Email Sending Function (Replace with a proper implementation) ---
// function send_email($to, $subject, $message, $headers) {
//     //  This is a placeholder. Use a library like PHPMailer for real email sending.
//     //  PHPMailer requires configuration (SMTP server, username, password, etc.)
//     //  Example (using placeholder values - DO NOT USE IN PRODUCTION):
//     //  $smtp = new PHPMailer\PHPMailer\PHPMailer();
//     //  $smtp->SMTPDebugEnable = false; // Set to true for debugging
//     //  $smtp->Host = 'smtp.example.com';
//     //  $smtp->Port = 587;
//     //  $smtp->Username = 'your_email@example.com';
//     //  $smtp->Password = 'your_password';
//     //  $smtp->SetFrom('your_email@example.com', 'Your Name');
//     //  $smtp->AddAddress($to);
//     //  $smtp->Subject = $subject;
//     //  $smtp->Body = $message;
//     //  $smtp->AltBody = "Plain text version of the email";
//     //  if ($smtp->send()) {
//     //      echo "Email sent successfully!
";
//     //  } else {
//     //      echo "Failed to send email.
";
//     //  }
// }

?>
