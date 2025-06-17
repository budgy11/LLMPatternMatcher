
    <form method="post" action="forgot_password.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your email address">
        <button type="submit">Reset Password</button>
    </form>

    <br>
    <a href="login.php">Back to Login</a>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate the email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists in the database
  $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($user)) {
    return "User with this email does not exist.";
  }

  // 3. Generate a unique, random token for the reset link
  $token = bin2hex(random_bytes(32));

  // 4. Hash the token (important for security)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 5. Store the token in the database associated with the user
  $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
  $stmt->execute([$hashed_token, time() + 3600, $email]); // Token expires in 1 hour

  // 6.  Send the reset email (Replace with your email sending logic)
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
  $headers = "From: your_email@example.com";  // Replace with your email address
  mail($to, $message, $headers);


  return "Password reset email sent to $email.  Please check your inbox (and spam folder!).";
}

// Example usage:  (Only for testing - usually handled through a form)
// if (isset($_GET['reset'])) {
//   $resetToken = $_GET['reset'];
//   $resetMessage = forgotPassword($resetToken);
//   echo $resetMessage;
// }
//
//  You'd typically handle this through a form submission with a POST request.
//  For example:
//  <form method="post" action="your_script.php">
//    <input type="hidden" name="email" value="user@example.com">
//    <button type="submit">Request Password Reset</button>
//  </form>
?>
