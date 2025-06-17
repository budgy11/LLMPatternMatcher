

<?php

/**
 * Forgotten Password Function
 *
 * This function handles the process of resetting a user's password.
 * It generates a unique token, sends an email with a password reset link,
 * and optionally allows the user to reset their password using that link.
 *
 * @param string $email The email address of the user who wants to reset their password.
 * @param string $reset_password_link_expiry  The expiry time (in seconds) for the password reset link. Defaults to 3600 (1 hour).
 * @return bool True if the password reset process initiated successfully, false otherwise.
 */
function forgot_password(string $email, string $reset_password_link_expiry = 3600) {

  // 1. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 2. Store the token and user ID in the database (assuming you have a database connection)
  // Replace this with your actual database interaction code.  This is a placeholder.
  $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your credentials
  try {
    $stmt = $db_connection->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, time() + $reset_password_link_expiry]);
    $reset_id = $db_connection->lastInsertId(); // Get the ID of the newly inserted row.
  } catch (PDOException $e) {
    error_log("Error creating password reset record: " . $e->getMessage()); // Log the error
    return false;
  }

  // 3. Prepare the password reset link
  $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

  // 4. Send the email
  $to = $email;
  $subject = 'Password Reset';
  $message = "To reset your password, please click on the following link: " . $reset_link;
  $headers = 'From: your_email@example.com' . "\r
";

  if (mail($to, $subject, $message, $headers)) {
    // Password reset email sent successfully
    return true;
  } else {
    // Failed to send email
    error_log("Failed to send password reset email to " . $email); // Log the error
    return false;
  }
}


// Example Usage (Simulated)
// You'll need to implement the actual email sending and database interaction
//  The following is just to demonstrate how you would call the function
$user_email = 'test@example.com'; // Replace with a valid email
if (forgot_password($user_email)) {
    echo "Password reset email sent to " . $user_email . "
";
} else {
    echo "Failed to initiate password reset for " . $user_email . "
";
}

?>
