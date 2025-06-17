

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token and sends an email
 * containing a link to reset the password.
 *
 * @param string $email The email address of the user to reset the password for.
 * @param string $token A unique token to verify the request. (Optional, used for security)
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token = '') {
  // 1. Check if the email exists in the database
  $user = get_user_by_email($email);  // Implement this function - See Example Below
  if (!$user) {
    return false; // User does not exist
  }

  // 2. Generate a unique token
  $token = generate_unique_token(); // Implement this function - See Example Below

  // 3. Store the token in the database, associated with the user's email
  save_token_to_database($token, $email); // Implement this function - See Example Below


  // 4. Build the password reset link
  $reset_link = "/reset_password?token=" . urlencode($token) . "&email=" . urlencode($email);

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Please click the following link to reset your password: " . $reset_link;

  $headers = "From: Your Website <noreply@yourwebsite.com>";  // Replace with your actual email address

  $result = send_email($email, $subject, $message, $headers); // Implement this function - See Example Below
  if ($result === true) { // Assuming send_email returns true on success
    return true;
  } else {
    // Handle email sending failure (log, display error, etc.)
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}


// ------------------------------------------------------------------
//  Placeholder functions - You *MUST* implement these!
// ------------------------------------------------------------------

/**
 * Retrieves a user from the database based on their email address.
 *
 * @param string $email The email address to search for.
 * @return object|null User object if found, null otherwise.
 */
function get_user_by_email(string $email): ?object {
  // **IMPORTANT:** Replace this with your actual database query.
  // This is just a placeholder.
  // Example using mysqli:
  // $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  // if (!$conn) {
  //   die("Connection failed: " . mysqli_connect_error());
  // }

  // $sql = "SELECT * FROM users WHERE email = '$email'";
  // $result = mysqli_query($conn, $sql);

  // if (mysqli_num_rows($result) > 0) {
  //   $row = mysqli_fetch_assoc($result);
  //   return $row;
  // } else {
  //   return null;
  // }

  // **Dummy User Object** - Remove this when integrating with your database
  $user = (object) [
    'id' => 1,
    'email' => 'test@example.com',
    'password' => 'hashed_password'
  ];
  return $user;
}


/**
 * Generates a unique, time-based token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
  return bin2hex(random_bytes(32)); // More secure than generating a random string
}


/**
 * Saves the token to the database, associated with the user's email.
 *
 * @param string $token The token to save.
 * @param string $email The email address to associate with the token.
 * @return void
 */
function save_token_to_database(string $token, string $email) {
  // **IMPORTANT:** Implement your database logic here.
  // Example using mysqli:
  // $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  // if (!$conn) {
  //   die("Connection failed: " . mysqli_connect_error());
  // }

  // $sql = "INSERT INTO tokens (email, token, expiry_date) VALUES ('$email', '$token', NOW())";
  // if (mysqli_query($conn, $sql)) {
  //   //  Success
  // } else {
  //   // Handle error
  // }

  // **Dummy Database Logic** - Remove this when integrating with your database
  //  This just stores the token in a variable to demonstrate functionality
  $_SESSION['reset_token'] = $token;
  $_SESSION['reset_expiry'] = date('Y-m-d H:i:s', time() + 3600); // Expires in 1 hour
}



/**
 * Sends an email.
 *
 * @param string $to       The recipient's email address.
 * @param string $subject  The email subject.
 * @param string $body     The email body.
 * @param string $headers  Email headers.
 * @return bool True on success, false on failure.
 */
function send_email(string $to, string $subject, string $body, string $headers) {
  // **IMPORTANT:**  Replace this with your actual email sending code.
  // This is just a placeholder.  Use a library like PHPMailer.

  // Example using PHPMailer (requires installation and configuration)
  // require_once 'PHPMailer/PHPMailerAutoload.php';
  // $mail = new PHPMailer();
  // $mail->Mailer = 'PHPMailer';
  // $mail->SMTPDebugEnable = false; // Set to true for debugging
  // $mail->isSMTP();                       // Set to true for SMTP
  // $mail->Host       = 'smtp.example.com';
  // $mail->SMTPAuth   = true;                    // Enable SMTP authentication
  // $mail->Username   = 'your_smtp_username';
  // $mail->Password   = 'your_smtp_password';
  // $mail->Port = 587;                         // Port for submission
  // $mail->SetFrom('your_website@example.com', 'Your Website');
  // $mail->Subject = $subject;
  // $mail->Body = $body;
  // $mail->AltBody = $body;
  // $mail->AddAttachment('attachment.php', 'Attachment name');  // Add attachments
  // $result = $mail->send($to, $headers);

  // **Dummy Email Sending** - Remove this when integrating with your email provider
  // For demonstration purposes, just return true.
  return true;
}
?>
