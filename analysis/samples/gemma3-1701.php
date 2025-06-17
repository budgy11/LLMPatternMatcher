
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with email and password fields.

// Function to handle the forgot password request
function forgotPassword($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists in the database
  $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "User not found.";
  }

  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 4.  Prepare the password reset token
  $reset_token = $token . "_" . $timestamp = date("Y-m-d H:i:s", time());


  // 5. Hash the password (Important for security!)
  $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

  // 6.  Store the token in the database associated with the user
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE email = ?");
  $stmt->execute([$token, date("Y-m-d H:i:s", time() + 60 * 60 * 24), $email]); // Store for 24 hours

  // 7. Send the password reset email (Example - Customize this!)
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a> to reset your password.";
  $headers = "From: your_email@example.com" . "\r
";

  mail($to, $message, $headers);

  return "Password reset email sent. Please check your inbox.";
}

// Example Usage (This would typically be called from a form submission)
// You would validate the form input and then call this function.

// Example Form Handling (Illustrative - Adjust to your form structure)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_GET["reset"];
    //Security - Sanitize the Reset Token
  $reset_token = filter_var($reset_token, FILTER_SANITIZE_STRING);
    if($reset_token == ""){
      $response = "Invalid Reset Token";
    }else{
      $response = forgotPassword($email);
    }


}else{
  $response = "Please fill in the email field.";
}
?>
