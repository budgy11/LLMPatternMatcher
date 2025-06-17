

<?php

// Assume you have a database connection established and a table named 'users' 
// with columns 'id', 'email', and 'password'

// Function to reset a password
function forgotPassword($email, $newPassword, $dbConnection) {
  // 1. Validate Input (Important for security)
  if (empty($email) || empty($newPassword)) {
    return 'Invalid email or password.';
  }

  // 2. Check if the email exists in the database
  $stmt = $dbConnection->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return 'Email not found.';
  }

  // 3. Hash the new password (Very Important for Security!)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the password in the database
  $stmt = $dbConnection->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$hashedNewPassword, $user['id']]);

  if ($stmt->rowCount() === 0) {
    return 'Failed to update password.';
  }

  return 'Password reset successfully. Check your email for further instructions.';
}


// Example Usage (Illustrative - Adapt to your application)

// Assuming $dbConnection is your PDO database connection
// $email = $_POST['email']; // Get email from form
// $newPassword = $_POST['new_password'];

// $result = forgotPassword($email, $newPassword, $dbConnection);
// echo $result;


//  ---------------------  Security Considerations  ---------------------

// 1.  Input Validation:  *Crucially* validate the email format
//     using regex or a library to prevent malicious input.  Also, consider
//     limiting password length, character types, etc., for stronger security.

// 2. Password Hashing: ALWAYS use a strong hashing algorithm (PASSWORD_DEFAULT)
//    to store passwords.  Never store passwords in plain text.

// 3. Email Verification (Highly Recommended):  Instead of just resetting the password,
//    send a verification email with a unique token. The user can then click the
//    link in the email to confirm the new password. This provides an extra layer
//    of security and ensures the user actually initiated the reset.  See below for example.

// 4. Rate Limiting:  Implement rate limiting to prevent brute-force attacks.
//    Limit the number of password reset requests from a single IP address or user.

// 5. Session Management:  Use sessions or tokens to track the password reset process.


// -------------------- Example with Email Verification --------------------
// (This is a more robust and secure approach)

// function forgotPasswordWithVerification($email, $dbConnection) {
//     // ... (Same input validation as before) ...

//     // Generate a unique token
//     $token = bin2hex(random_bytes(32)); // Secure random bytes

//     // Store the token and user ID in a temporary table (or use a session)
//     $stmt = $dbConnection->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())");
//     $stmt->execute([$user['id'], $token]);
//     $resetId = $dbConnection->lastInsertId();


//     // Send an email with a verification link
//     $verificationLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
//     $subject = 'Password Reset';
//     $message = "Click the link above to reset your password.";
//     $headers = 'From: your_email@example.com' . "\r
";
//     mail($email, $subject, $message, $headers);


//     return $verificationLink; // Return the link for the user to click
// }


?>
