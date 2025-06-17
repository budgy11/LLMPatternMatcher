

<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process, generating a temporary
 * password and sending it to the user's email.
 *
 * @param string $email The user's email address.
 * @param string $resetLinkBase URL where the reset links will be generated.  
 *                              Should be a URL that doesn't require authentication.
 * @return bool True if the reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $resetLinkBase) {
  // 1. Sanitize and Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Temporary Password (e.g., random string)
  $resetPassword = generateRandomString(8); //  Adjust length as needed
  
  // 3. Store the Reset Token in the Database
  $token = hash('sha256', $resetPassword . time());  // Use a more secure hashing algorithm
  
  // IMPORTANT: In a real-world application, you would store this data in a database.
  // For demonstration purposes, we'll use a simple array.
  $resetData = [
    'email' => $email,
    'token' => $token,
    'expiry' => time() + 3600  // Expire after 1 hour
  ];
  
  // 4.  Simulate Database Storage (Replace with your database logic)
  // In a real app, you'd use something like:
  // $db->insert('forgot_passwords', $resetData); 
  
  // 5. Generate the Reset Link
  $resetLink = $resetLinkBase . '?token=' . urlencode($token);

  // 6. Send the Reset Email
  $to = $email;
  $subject = 'Password Reset';
  $message = "To reset your password, please click on the following link: " . $resetLink;
  $headers = "From: YourWebsite <noreply@yourwebsite.com>"; // Customize the sender address
  
  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send reset email to: " . $email); // Log email sending failure
    return false;
  }
}

/**
 * Generates a random string of the specified length.
 *
 * @param int $length The desired length of the random string.
 * @return string The generated random string.
 */
function generateRandomString(int $length) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $randomString = '';
  $length = $length;
  for ($i = 0; $i < $length; $i++) {
    $char = $characters[rand(0, strlen($characters) - 1)];
    $randomString .= $char;
  }
  return $randomString;
}

// --- Example Usage ---
// Replace with your actual email address
$email = "testuser@example.com";
$resetLinkBase = "https://yourwebsite.com/reset-password";  // Change this to your website's base URL

if (forgotPassword($email, $resetLinkBase)) {
  echo "Reset email sent successfully! Check your inbox.";
} else {
  echo "Failed to send reset email. Please check your email settings and try again.";
}

?>
