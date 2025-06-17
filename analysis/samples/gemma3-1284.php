

<?php

/**
 * Forgot Password Function
 *
 * This function handles the password reset process.  It generates a unique,
 * time-limited token, sends an email to the user with a link to reset their
 * password, and stores the token and expiration date in the database.
 *
 * @param string $email The user's email address.
 * @param string $resetUrl  The base URL for the reset password page.
 * @param string $secretKey  A secret key used for signing the token (important for security).
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $resetUrl, string $secretKey) {
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 2.  Set Expiration Time -  Example: 1 hour
  $expirationTime = time() + 3600; // Add 1 hour to the current timestamp

  // 3. Hash the Token with the Secret Key
  $hashedToken = hash_file('sha256', $token); // Use a strong hashing algorithm

  // 4. Store the Token and Expiration Time in the Database
  $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); // Replace with your database credentials.  Ensure PDO is properly configured.
  try {
    $stmt = $db->prepare("INSERT INTO forgot_password_tokens (email, token, hashed_token, expiration_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $token, $hashedToken, $expirationTime]);
    return true;
  } catch (PDOException $e) {
    // Handle database errors (e.g., email already exists)
    error_log("Error creating forgot password token: " . $e->getMessage()); //Log for debugging
    return false;
  }

  // 5. Send the Password Reset Email (Example -  You'll need to adapt this)
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " . $resetUrl . "/reset?token=" . $token;
  $headers = "From: your_email@example.com\r
"; // Replace with your sender email

  mail($email, $subject, $message, $headers); // Uses PHP's built-in mail function.  Consider using a library like PHPMailer.



  return true;
}


// Example Usage (for demonstration - adapt to your application)
//  This is for testing only.  Don't use this directly in production without proper error handling and security measures.
/*
$email = "testuser@example.com";
$resetUrl = "http://localhost/reset_password";
$secretKey = "your_secret_key"; //  Generate a strong, random secret key.  Don't hardcode it in production.

if (forgot_password($email, $resetUrl, $secretKey)) {
  echo "Password reset request sent successfully!";
} else {
  echo "Failed to send password reset request.";
}
*/



//  Important Security Considerations:
//  - **Secret Key:**  Generate a truly random and strong secret key. Store it securely (e.g., environment variables, a secrets management system).  Never commit this to your codebase.
//  - **Token Expiration:** Short expiration times are crucial for security. 1 hour is a good starting point.
//  - **Hashing:**  Always hash the token with a strong hashing algorithm like SHA256.
//  - **Input Validation & Sanitization:**  Validate and sanitize the email address to prevent injection attacks.
//  - **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.
//  - **HTTPS:**  Always use HTTPS to encrypt the communication between the user and your server.
//  - **Database Security:**  Protect your database from unauthorized access.  Use parameterized queries to prevent SQL injection.

?>
