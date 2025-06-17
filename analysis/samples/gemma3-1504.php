

<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset token was generated and sent, false otherwise.
 */
function forgot_password(string $email)
{
  // 1. Validate Email (basic validation - you can make this more robust)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random token

  // 3. Hash the Token (Important for Security!)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4. Store the Token and User ID in the Database
  $query = "INSERT INTO password_resets (user_id, token, expires_at) 
             VALUES (:user_id, :token, :expires_at)";
  $stmt = $db->prepare($query);
  $stmt->bind_param("iii", $email, $token, time() + 3600); // Store for 1 hour
  $result = $stmt->execute();

  if (!$result) {
    error_log("Error inserting token into database: " . $db->error); // Log for debugging
    return false;
  }

  // 5.  Send the Reset Link (Email)
  $to = $email;
  $subject = 'Password Reset Link';
  $message = "Please click the following link to reset your password: " . 
             '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($token) . '">' . 
             '<a href="' . base_url() . '/reset-password?token=' . urlencode($token) . '</a>';
  $headers = "From: your_email@example.com"; // Replace with your email

  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Error sending email: " . mail($to, $subject, $message, $headers));
    // In a real application, you'd want to handle this error more gracefully
    //  (e.g., display an error message to the user)
    return false;
  }
}



// Example Usage (in your form submission handling code)
// Assuming you have a form with an 'email' input field
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  if (forgot_password($email)) {
    echo "Password reset email has been sent to " . $email;
  } else {
    echo "Failed to generate password reset link. Please try again.";
  }
}
?>
