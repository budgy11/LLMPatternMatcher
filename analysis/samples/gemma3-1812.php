

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token, sends an email with a link to reset the password,
 * and stores the token in the database.
 *
 * @param string $email The email address of the user.
 * @param string $baseUrl The base URL of your website.  Used for the reset link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 2. Prepare the Reset Link
  $resetLink = $baseUrl . '/reset-password?token=' . $token;

  // 3. Prepare the Email Message
  $subject = "Password Reset Request";
  $message = "Click the following link to reset your password: " . $resetLink;
  $headers = "From: " .  $baseUrl . "\r
";
  $headers .= "Reply-To: " . $email . "\r
";

  // 4. Send the Email (using PHPMailer -  Install via Composer: `composer require phpmailer/phpmailer`)
  if (sendEmail($email, $subject, $message, $headers)) {
    // 5. Store the Token in the Database
    saveToken($email, $token);
    return true;
  } else {
    // Handle email sending failure -  Log it or show an error message
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}


/**
 * Placeholder for sending email (Replace with your actual email sending logic).
 *  This is a placeholder function.  You *must* implement this using a real email library.
 *  Example using PHPMailer:
 *  $mail = new PHPMailer\PHPMailer\PHPMailer();
 *  $mail->SMTPDebugEnable = true;
 *  // Configure SMTP settings (replace with your details)
 *  $mail->isSMTP();
 *  $mail->Host       = 'smtp.gmail.com';
 *  $mail->SMTPAuth   = true;
 *  $mail->Username   = 'your_email@gmail.com';
 *  $mail->Password   = 'your_password';
 *  $mail->Port = 587;
 *  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
 *
 *  $mail->setFrom($email, $email);
 *  $mail->addAddress('user@example.com'); // Change to user's email
 *  $mail->Subject = $subject;
 *  $mail->Body    = $message;
 *
 *  if(!$mail->send()){
 *     return false;
 *  }
 *  return true;
 *
 * @param string $email       The email address.
 * @param string $subject     The email subject.
 * @param string $message     The email body.
 * @param string $headers     Email headers.
 * @return bool True on success, false on failure.
 */
function sendEmail(string $email, string $subject, string $message, string $headers): bool
{
  // Replace this with your actual email sending logic
  // For testing, you can simply echo the email details.
  error_log("Sending email: To: " . $email . "
Subject: " . $subject . "
Message: " . $message . "
Headers: " . $headers);
  return true; // Placeholder - Always return true for testing.  Implement real sending here.
}


/**
 * Placeholder for saving the token in the database.  Replace with your database logic.
 *
 * @param string $email       The user's email address.
 * @param string $token       The generated token.
 */
function saveToken(string $email, string $token): void
{
  // Placeholder - Replace with your database saving logic
  // Example (using a simple array - **NOT RECOMMENDED FOR PRODUCTION**):
  // $_SESSION['reset_token'] = $token;
  // This is just a demo - for a real application, use a database.

  // Example using database (using PDO):
  // $db = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
  // $stmt = $db->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())");
  // $stmt->execute([$email, $token]);
}

// Example Usage (for testing - do not use this in your application directly)
// In a real application, you'd get the email from a form submission.
// For this example, we're hardcoding the email.
$baseUrl = 'http://localhost/your-website';  // Replace with your website's base URL

// Simulate a user requesting a password reset
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    if (forgotPassword($email, $baseUrl)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
    }
}
?>
