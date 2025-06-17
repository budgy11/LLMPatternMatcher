

<?php

// IMPORTANT:  This is a simplified example for demonstration.
// In a production environment, you MUST:
// 1. Use a strong, secure password reset mechanism (e.g., token-based)
// 2. Hash passwords securely
// 3. Implement proper error handling and logging
// 4. Sanitize and validate all input thoroughly
// 5. Protect against brute-force attacks (e.g., rate limiting)


// Simulate a user database (replace with your actual database connection)
$users = [
    'john.doe@example.com' => ['password' => 'secret_password', 'salt' => 'random_salt'],
    'jane.smith@example.com' => ['password' => 'another_secret', 'salt' => 'another_salt']
];


function forgot_password($email) {
  // 1. Check if the email exists
  if (!isset($users[$email])) {
    return "Email not found.";
  }

  // 2. Generate a unique token (for security)
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token
  // In a production environment, you'd use a more robust token generation method
  // and store the token in the database, associated with the user's email.

  // 3. Prepare reset link (for demonstration - DO NOT USE THIS IN PRODUCTION)
  $reset_url = "https://yourwebsite.com/reset_password?token=$token"; // Replace with your website URL


  // 4. Send an email to the user with the reset link
  $subject = "Password Reset Link";
  $message = "Click the link below to reset your password:
" . $reset_url;
  $headers = "From: yourwebsite@example.com\r
";  // Replace with your sender email
  mail($email, $message, $headers);  // Use mail() - better alternatives exist (see below)

  return "Password reset link sent to your email.  Check your inbox!";
}


// Example usage (for testing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgot_password($email);
  echo "<p>" . $result . "</p>";
}
?>
