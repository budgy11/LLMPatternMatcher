

<?php

// Assuming you have a database connection established
// (e.g., $db is your database connection object)

function forgotPassword($email) {
  // 1. Validate Email (important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random token.

  // 3. Hash the Token (Important for security - store the hash, not the token itself)
  $hashedToken = password_hash($token, PASSWORD_DEFAULT); // Use password_hash for secure hashing

  // 4. Prepare the Reset Link Data
  $resetLinkData = [
    'token' => $token,
    'email' => $email,
    'timestamp' => time()  // Add a timestamp for expiration
  ];

  // 5. Store the Token in the Database (or cache)
  //  -- This is the crucial part.  Adapt to your database/cache system
  // Example using a simple array (replace this with your actual database interaction)
  global $resetLinks; // Assuming $resetLinks is a global array to store reset links
  $resetLinks[$email] = $resetLinkData;

  // 6.  Create the Reset Link (URL)
  $resetLink = "/reset_password.php?token=" . urlencode($token);

  // 7.  Return the Reset Link to the User (or display a message)
  return $resetLink;
}


// Example Usage (In your forgot_password.php file):
// Assuming you have a form to collect the email address
// if (isset($_POST['email'])) {
//   $email = $_POST['email'];
//   $resetLink = forgotPassword($email);
//   echo "<p>We've sent a password reset link to: " . htmlspecialchars($email) . "</p>";
//   echo "<a href='" . htmlspecialchars($resetLink) . "'>Reset Password</a>";
// }
//
//  And in your reset_password.php file (to verify the token):
//
//  <?php
//  if (isset($_GET['token'])) {
//      $token = $_GET['token'];
//
//      // 1. Retrieve the reset link data from the database
//      global $resetLinks;
//      $resetLinkData = $resetLinks[$token];
//
//      // 2. Verify the Token
//      if ($resetLinkData['token'] === $token && password_verify($token, $resetLinkData['token'])) { // Using password_verify
//          // 3.  Redirect to the reset password form (with the token)
//          //    You'll need a reset_password_form.php file.
//          //   Pass the token to the form so the user can enter their new password.
//          // Example:
//          // header("Location: reset_password_form.php?token=" . urlencode($token));
//          // exit();
//
//          // 4.  (Optionally) You might want to clear the reset link after verification
//          //    to improve security.
//
//       } else {
//         // Token is invalid or expired
//         echo "<p>Invalid or expired reset link.</p>";
//       }
//    } else {
//      echo "<p>Reset link not provided.</p>";
//    }
//?>
