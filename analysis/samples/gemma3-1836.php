
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit" name="reset_button">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // 2. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32));  // Secure random bytes

  // 3. Store the token and user ID in the database
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) 
                         VALUES (?, ?, NOW())");
  $stmt->execute([$user['id'], $token]);

  // 4. Send the password reset email (implementation details depend on your email setup)
  $resetLink = "https://yourwebsite.com/reset-password?token=" . $token; // Replace with your actual website URL
  sendResetPasswordEmail($email, $resetLink);  //  See helper function below for details

  return true; // Password reset request submitted successfully
}


//Helper function to send the password reset email.  Replace with your email sending logic.
function sendResetPasswordEmail($email, $resetLink) {
    //  Implement your email sending logic here.  This is just a placeholder.

    // Example using a simple email copy/paste:
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:
" . $resetLink;
    $headers = "From: yourname@example.com\r
";

    mail($to, $message, $headers);
    // Alternatively, use a more robust email library (e.g., PHPMailer) for better control and handling.
}



// Example Usage (Illustrative):
// $email = $_POST['email']; // Get email from form submission
// if (isset($email)) {
//   if (forgotPassword($email)) {
//     echo "Password reset email sent to " . $email;
//   } else {
//     echo "Invalid email or email already exists.";
//   }
// }

?>
