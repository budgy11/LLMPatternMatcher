

<?php

// Assuming you have a database connection established (e.g., $db)
// and that you have a user table with an 'email' column.

function forgot_password($email, $db) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Use a strong random token

  // 3. Store the token and user ID in the database (for security)
  $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
  $stmt->execute([$token, time(), $email]); // Expires after a certain time

  // 4.  Create a reset password link
  $reset_link = "http://yourwebsite.com/reset_password?token=" . urlencode($token); //Replace with your actual website URL

  // 5.  Send an email (replace with your email sending logic)
  $to = $email;
  $subject = 'Password Reset';
  $message = "To reset your password, please click on the following link: " . $reset_link;

  //  Example using phpmailer (requires installation: composer require phpmessaging/phpmailer)
  // use PHPMailer\PHPMailer\PHPMailer;
  // use PHPMailer\PHPMailer\SMTP;

  // $mail = new PHPMailer(true); // Alias SMTP as true to use mail()
  // $mail->isSMTP();
  // $mail->Host = 'smtp.example.com';
  // $mail->Username = 'your_email@example.com';
  // $mail->Password = 'your_password';
  // $mail->Port = 587;
  // $mail->SMarthost = 'smtp.example.com';
  // $mail->setFrom($email, $email);
  // $mail->addAddress($email, $email);
  // $mail->isHTML(false);  // Set this to true if you want to send HTML emails
  // $mail->Subject = $subject;
  // $mail->Body = $message;
  // $mail->send();



  return $reset_link;  // Return the link to the user
}

// Example Usage (assuming $db is your PDO database connection)
// $email = $_POST['email']; // Get email from form submission

// if (isset($email)) {
//   $reset_link = forgot_password($email, $db);

//   if ($reset_link) {
//     echo "<p>An email has been sent to " . $email . " with instructions to reset your password.</p>";
//   } else {
//     echo "<p>Invalid email or password not found.</p>";
//   }
// } else {
//   echo "<p>Please enter your email address.</p>";
// }

?>
