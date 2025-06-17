
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

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Reset Password">
    </form>

    <p>If you forgot your password, please contact us for assistance.</p>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with an 'email' column.

// 1. Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 2. Get the email from the form
  $email = $_POST["email"];

  // 3. Validate the email format (basic validation - improve as needed)
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // 4. Check if the email exists in the database
    $result = mysqli_query($db, "SELECT id, email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($result) > 0) {
      // 6. Generate a unique token
      $token = bin2hex(random_bytes(32)); 

      // 7.  Prepare and execute the update query
      $update_query = "UPDATE users SET password_reset_token = '$token', password_reset_token_expiry = NOW() WHERE email = '$email'";
      mysqli_query($db, $update_query);

      // 8.  Send a reset email (Example) -  You'll need to adapt this to your email sending logic
      $to = $email;
      $subject = "Password Reset";
      $message = "Click this link to reset your password: " . $_SERVER["REQUEST_URI"] . "?token=" . $token;
      $headers = "From: your_email@example.com"; // Change this!

      mail($to, $subject, $message, $headers);

      // 9. Redirect to a password reset page (or display success message)
      header("Location: password_reset_page.php?email=$email&token=$token");  //  Change to your reset page
      exit();
    } else {
      $error = "Email not found.";
    }
  }
}
?>
