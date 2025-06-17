
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Include the database configuration file (e.g., config.php)
// This file should contain your database connection details.
require_once 'config.php';

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Input (Basic)
  if (empty($email)) {
    error_log("Forgot Password: Empty email provided."); // Log error for debugging
    return false;
  }

  // 2. Check if the user exists
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($user)) {
    error_log("Forgot Password: User with email '$email' not found."); // Log error
    return false;
  }


  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator

  // 4.  Store the token in the database.  This is crucial.  Otherwise the reset link won't work.
  $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry  WHERE email = :email");
  $stmt->bindParam(':token', $token, PDO::PARAM_STR);
  $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + (3600)), PDO::PARAM_STR); // Token expires after 1 hour
  $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);
  $stmt->execute();


  // 5.  Send the password reset email
  $reset_link = base_url . "/reset-password?token=" . $token; // Generate the reset link

  $to = $user['email'];
  $subject = 'Password Reset';
  $message = "To reset your password, please click on this link: " . $reset_link;
  $headers = "From: " . get_option('admin_email') . "\r
"; // Use your admin email for the sender address
  mail($to, $subject, $message, $headers);


  return true;
}



// Example Usage (for testing - typically handled through a form submission)
// $email = $_POST['email'];  // Get email from form
// if (isset($email)) {
//     if (forgot_password($email)) {
//         echo "Password reset email has been sent to $email.";
//     } else {
//         echo "Error sending password reset email.";
//     }
// }



//  Helper function to get the base URL -  (This is a simplified example.  In a real application, you'd want to make this more robust,
//  potentially using a routing library or a configuration file to define the base URL.
function get_option(string $option) {
    //For demonstration purposes only.  Replace with your actual logic for retrieving the base URL.
    return 'http://yourwebsite.com/';
}


?>
