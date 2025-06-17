

<?php

// Assume you have a database connection established (e.g., $conn)
// and a table named 'users' with fields 'email' and 'password'

// Function to handle forgotten passwords
function forgotPassword($email, $conn) {
  // 1. Validate Email
  $email = trim($email); // Remove leading/trailing whitespace
  if (empty($email)) {
    return 'Error: Please enter your email address.';
  }

  // Check if the email exists in the database
  $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return 'Error: Email address not found.';
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32));  // Generates a 32-byte random binary string, then converts to hexadecimal

  // 3. Prepare Reset Link
  $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token . '&id=' . $user['id'];

  // 4. Send Password Reset Email (Requires Email Sending Function - example below)
  if (sendPasswordResetEmail($user['email'], 'Password Reset Link', $reset_link) !== true) {
    return 'Error: Could not send password reset email.';
  }

  return 'Password reset email has been sent to ' . $user['email'];
}


// -----------------------------------------------------------------
//  Example Email Sending Function (Replace with your actual email sending method)
// -----------------------------------------------------------------

function sendPasswordResetEmail($to, $subject, $link) {
  // **IMPORTANT:** Replace this with your actual email sending code.
  // This is a placeholder.  You need to use a real email sending library or function.
  //
  // Example using a simple (but insecure) mail function:
  //
  $headers = "From: your_email@example.com" . "\r
" .
            "Reply-To: your_email@example.com" . "\r
" .
            "X-Mailer: PHP";
  $message = "Please click the following link to reset your password: " . $link;
  return mail($to, $message, $headers);


  // **SECURITY WARNING:**  The mail() function is often unreliable and has security issues.
  //  Consider using a more robust email sending library (e.g., PHPMailer, SwiftMailer).
}

// -----------------------------------------------------------------
// Example Usage (for testing)
// -----------------------------------------------------------------

// Simulate a database connection
// (Replace this with your actual database connection)
$conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

// Example email input
$email = 'test@example.com';  // Replace with a valid test email

// Call the forgotPassword function
$result = forgotPassword($email, $conn);

// Display the result
echo $result;

?>
