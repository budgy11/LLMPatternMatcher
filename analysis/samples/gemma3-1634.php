

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email)
{
  // Validate the email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided."); // Log the error for debugging
    return false;
  }

  // 1. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 2.  Store the token and the user's ID in the database (for security)
  //   -  This is crucial to prevent unauthorized password resets.
  //   -  Consider using a hash for the token to enhance security.
  //   -  We're using a simple example here; in a real-world application,
  //     you'd probably use a more robust database table and hashing.

  // Assuming a user table with columns 'id', 'email', 'password', and 'reset_token'
  //  and a function to insert a new record
  global $db; // Assuming $db is your database connection object

  $query = "INSERT INTO users (email, reset_token) VALUES ('$email', '$token')";
  if (!$db->query($query)) {
    error_log("Error inserting reset token: " . $db->error);
    return false;
  }


  // 3. Send the password reset email
  $to = $email;
  $subject = 'Password Reset';
  $message = "To reset your password, please click on this link: " .  $_SERVER['REQUEST_SCHEME'] . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . urlencode($token); // Use a unique link
  $headers = "From: noreply@example.com\r
"; // Replace with your actual noreply email
  if (mail($to, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send password reset email.");
    // Optionally, you could delete the token from the database if the email fails
    // to prevent misuse.
    // $db->query("DELETE FROM users WHERE email = '$email' AND reset_token = '$token'");
    return false;
  }
}


// Example Usage (For testing - DO NOT use this directly in your application!)
//
// $email = "test@example.com";
// if (forgotPassword($email)) {
//   echo "Password reset email sent to " . $email . "
";
// } else {
//   echo "Failed to send password reset email.
";
// }
//

//  Important Considerations and Improvements:

// 1. Security:
//    - **Token Hashing:**  Never store the raw token in the database.  Use a strong hashing algorithm (e.g., bcrypt, Argon2) to hash the token before storing it.  This prevents attackers from easily stealing tokens.
//    - **Token Expiry:** Implement token expiry.  Tokens should only be valid for a limited time (e.g., 30 minutes, 1 hour).  This limits the window of opportunity for an attacker to use a compromised token.
//    - **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks on the password reset endpoint.
//    - **Input Validation:** Thoroughly validate all input, including the email address and the token during password reset.  Sanitize user input to prevent SQL injection vulnerabilities.

// 2. Database Interaction:
//    - Use prepared statements (parameterized queries) to prevent SQL injection.
//    - Use a database connection object for efficient database interaction.
//    - Error handling:  Properly handle database errors.

// 3. Email Sending:
//    - Use a reliable email sending service (e.g., SendGrid, Mailgun, AWS SES) instead of PHP's `mail()` function.  The `mail()` function is often unreliable.
//    - Implement error handling for email sending failures.

// 4.  User Interface:
//     - Provide clear instructions to the user on what to expect after clicking the reset link.
//     -  Implement a confirmation page to show the user that the reset was successful.

// 5.  Testing:
//     - Thoroughly test the password reset functionality, including different email addresses, scenarios where the email fails to send, and scenarios where the token is invalid.

?>
