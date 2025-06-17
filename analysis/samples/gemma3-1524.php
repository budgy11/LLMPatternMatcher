

<?php

// Important: Replace 'your_database_file.db' with your actual database file
// and 'your_email' with the email address for notifications.
// This is a VERY basic example and should be adapted for a real-world scenario.

// Database configuration (Adjust these to match your setup)
$db_file = 'your_database_file.db'; // e.g., 'users.db'
$email_notification = 'your_email@example.com';

// Function to reset password
function forgotPassword($email, $db_file, $email_notification) {
  // 1. Validate Input (Crucial for security)
  if (empty($email)) {
    return 'Error: Email address is required.';
  }

  // 2. Fetch User Data
  $users = [];
  if (file_exists($db_file)) {
    $users = json_decode(file_get_contents($db_file), true);
  }


  // 3. Check if user exists
  if (!isset($users[$email])) {
    return 'Error: User not found.';
  }

  // 4. Generate a unique, secure token
  $token = bin2hex(random_bytes(32));  // Creates a 32-byte random string
  // Generate a unique token  (Better than just using a string)

  // 5. Update User Data with Token and Reset Link
  $users[$email]['reset_token'] = $token;
  $users[$email]['reset_link'] = 'https://yourdomain.com/reset_password?token=' . $token; // Replace with your domain

  // 6. Save Updated Data to Database
  if (file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT)) === FALSE) {
    return 'Error: Could not save reset token.';
  }

  // 7. Send Password Reset Email
  $subject = 'Password Reset Request';
  $message = "To reset your password, please click on the following link: " . $users[$email]['reset_link'];
  $headers = "From: Your Website <your_email@example.com>"; // Adjust from address

  if (mail($email, $subject, $message, $headers)) {
    return 'Password reset link sent to your email.';
  } else {
    return 'Error: Could not send email.';
  }
}

// Example Usage (for testing)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];

  $result = forgotPassword($email, $db_file, $email_notification);
  echo "<p>Result: " . htmlspecialchars($result) . "</p>";
}
?>
