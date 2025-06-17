

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token and sends an email to the user
 * with a link to reset their password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your website.  This is necessary for generating the reset link.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
  // Sanitize input -  VERY IMPORTANT
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log the error.  Don't just echo.
    return false;
  }


  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Use a strong, random token


  // 2. Store the Token and User ID in the Database (Simplified Example - Replace with your actual database logic)
  // IMPORTANT: NEVER store passwords in plaintext. Always hash them.
  // This example uses an array for simplicity.  In a real application, you'd use a database.
  $user_id = getUserIdByEmail($email); // Assume this function retrieves the user ID from your database

  if ($user_id === false) {
    error_log("User not found with email: " . $email);
    return false;
  }

  $reset_data = [
    'user_id' => $user_id,
    'token' => $token,
    'expiry' => time() + (3600) // Token expires after 1 hour (adjust as needed)
  ];


  // 3.  Save the token and expiry into the database for the user.
  // In a real application, this would be a database insert.
  saveResetToken($reset_data); // Assume this function saves the token and expiry


  // 4.  Create the Reset Link
  $reset_url = $baseUrl . "/reset-password?token=" . urlencode($token);

  // 5.  Send the Email
  $subject = "Password Reset Request";
  $message = "Click the link below to reset your password:
" . $reset_url . "

This link will expire in one hour.";
  $headers = "From: " .  "Your Website <noreply@yourwebsite.com>" . "\r
"; // Replace with your email address

  if (mail($email, $subject, $message, $headers)) {
      return true;
  } else {
      error_log("Failed to send email to " . $email);
      // Optionally, delete the token from the database here if sending the email fails.
      // This prevents the token from being used if the email couldn't be sent.
      // deleteResetToken($user_id, $token); // Add this function if you have it
      return false;
  }
}

// ************************************************************************
// Placeholder functions - Replace these with your actual database logic.
// ************************************************************************

/**
 * Placeholder function to retrieve the user ID from the email.  Replace with your database query.
 *
 * @param string $email The email address.
 * @return int|false The user ID, or false if the user is not found.
 */
function getUserIdByEmail(string $email): int|false
{
  // Replace this with your database query
  // Example (assuming you have a 'users' table with an 'email' column and an 'id' column):
  // return mysqli_query($db, "SELECT id FROM users WHERE email = '$email'") ? mysqli_fetch_assoc($result)['id'] : false;
  // Returning false when no user is found.
  return false;
}

/**
 * Placeholder function to save the reset token and expiry to the database.
 *
 * @param array $reset_data An array containing the user_id, token, and expiry.
 * @return void
 */
function saveResetToken(array $reset_data): void
{
  // Replace this with your database insert logic
  // Example (assuming you have a 'reset_tokens' table):
  // $query = "INSERT INTO reset_tokens (user_id, token, expiry) VALUES ('" . $reset_data['user_id'] . "', '" . $reset_data['token'] . "', '" . $reset_data['expiry'] . "')";
  // mysqli_query($db, $query);
  //  //  // Or, if using prepared statements:
  //  //  // $stmt = mysqli_prepare($db, "INSERT INTO reset_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
  //  //  // mysqli_stmt_bind_param($stmt, int, str, int);
  //  //  // mysqli_stmt_execute($stmt);
}


/**
 * Placeholder function to delete the reset token from the database.
 *
 * @param int $user_id The user ID.
 * @param string $token The token.
 * @return void
 */
function deleteResetToken(int $user_id, string $token): void
{
    // Replace this with your database delete logic.  For example:
    // mysqli_query($db, "DELETE FROM reset_tokens WHERE user_id = '$user_id' AND token = '$token'");
}



// Example Usage (for testing - replace with your actual website URL)
$baseUrl = "http://localhost:8000"; // Replace with your website URL

$email = "test@example.com"; // Replace with a valid email address

if (forgotPassword($email, $baseUrl)) {
  echo "Password reset email sent to " . $email . "
";
} else {
  echo "Failed to send password reset email.
";
}
?>
