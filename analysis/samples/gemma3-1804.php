

<?php

// Assuming you have a database connection established and named $db

function forgotPassword($email) {
  // 1. Validate the email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if the user exists in the database.
  $stmt = $db->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    // 3. Generate a unique, random, and secure password reset token
    $token = bin2hex(random_bytes(32)); // Use bin2hex for secure representation

    // 4. Update the user's record with the token
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->execute([$token, $email]);

    // 5.  Send an email to the user with the reset link.  (This is a placeholder)
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset_token=" . $token; // Use PHP_SELF for direct linking
    $headers = "From: your-email@example.com"; // Replace with your email address

    mail($email, $subject, $message, $headers);  //  Implement proper email sending (see notes)

    return 'Password reset email sent to ' . $email . '. Please check your inbox.';
  } else {
    return 'User not found.';
  }
}



// Example Usage (within your form processing logic)
// Assuming you have a form element with name "email"
$email = $_POST['email'];  // Get the email from the form

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password_button'])) {
    $result = forgotPassword($email);
    echo $result;
}
?>
