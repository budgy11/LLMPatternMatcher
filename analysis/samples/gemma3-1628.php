
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
// and a table 'users' with 'email' and 'password' columns.

// Function to handle password reset requests
function forgotPassword($email, $db) {
  // 1. Verify Email Existence
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");
  $user = $result->fetch_assoc();

  if (!$user) {
    return "Email not found.";
  }

  // 2. Generate a Unique Token and Store It
  $token = bin2hex(random_bytes(32)); // Generate a secure random token
  $db->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

  // 3.  Send a Password Reset Email (Example)
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>" . $_SERVER['PHP_SELF'] . "?reset_token=$token</a>";
  $headers = "From: your_email@example.com";

  mail($to, $message, $headers); // Replace your_email@example.com with your email.

  return "Password reset email sent to $email.";
}

// Example Usage (In your form submission handler)

//  $email = $_POST['email'];
//  $reset_token = $_GET['reset_token'];  //  Retrieves token from URL.
//  $result = forgotPassword($email, $db);
//  echo $result;
?>
