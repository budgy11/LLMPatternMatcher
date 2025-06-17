

<?php

/**
 * Forgets the user's password and initiates a password reset process.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset email was sent, false otherwise (e.g., invalid email).
 */
function forgotPassword(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address: " . $email); // Log the error for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Secure random bytes for token

  // 3. Store the Token and User ID in the Database
  //   (Replace this with your actual database interaction code)
  $db_result = database_insert_token($email, $token); // See function definition below

  if ($db_result === false) {
    error_log("Failed to store token in database for email: " . $email);
    return false;
  }


  // 4. Send the Password Reset Email
  $subject = "Password Reset";
  $message = "Please click the following link to reset your password: <a href='" . base_url() . "/reset-password?token=" . urlencode($token) . "'>Reset Password</a>"; // Build URL dynamically
  $headers = "From: " . get_admin_email() . "\r
"; // Replace with your admin email
  $result = mail($email, $subject, $message, $headers);

  if ($result === false) {
    error_log("Failed to send password reset email to: " . $email);
    // Consider retrying the email later or logging more details.
    // In a production environment, use a more robust email sending library
    // or service (e.g., SendGrid, Mailgun)
    return false;
  }

  return true;
}


/**
 * Placeholder function for database insertion.  Replace with your actual
 * database code. This function demonstrates the intended interaction.
 *
 * @param string $email The user's email address.
 * @param string $token The unique token generated.
 * @return bool True on successful insertion, false otherwise.
 */
function database_insert_token(string $email, string $token): bool
{
  // Simulate a database insertion (replace with your actual logic)
  // In a real application, you'd use a database query here.

  // For demonstration purposes, we'll just check if the email exists in a simple array.
  // This is highly insecure for a real application.

  $users = [
    'test@example.com' => '', // Replace with your user data
    'another@example.com' => '',
  ];


  if (isset($users[$email])) {
    // You might want to update an existing record or handle the case where the user
    // already has a token.
    return true;
  } else {
    return false; // Email not found, so insertion failed.
  }
}

/**
 *  Placeholder function to get the admin email.  Replace with your actual logic.
 * @return string
 */
function get_admin_email(): string
{
  return "admin@example.com";
}



// Example Usage (For Testing)
// This is just for testing the function.  Don't include this in your production code.
// Add the form elements and logic to submit the email.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  if (isset($email)) {
    if (forgotPassword($email)) {
      echo "Password reset email sent to " . $email;
    } else {
      echo "Failed to send password reset email.";
    }
  } else {
    echo "Invalid email address.";
  }
}
?>
