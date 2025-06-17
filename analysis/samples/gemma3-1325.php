

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Enter your email address:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php
require_once 'PHPMailer/src/PHPMailer.php'; // Adjust path if necessary
use PHPMailer\PHPMailer\PHPMailer;

// ... (rest of the code)

// In the forgot_password function:
$mail = new PHPMailer(true); //Partial class instantiation
$mail->isSMTP();                       // Set Development mode which is helpful for debugging.
$mail->Host       = 'smtp.example.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'your_smtp_username';
$mail->Password   = 'your_smtp_password';
$mail->Port = 587;  // Or 465 for SSL
$mail->SMART_HOST = true;
$mail->Encoding = "UTF-8";

$mail->isHTML(false);  // Set this to true if you're sending HTML content
$mail->Subject = "Password Reset Link";
$mail->Body    = "Click the link below to reset your password:
" . $reset_url;
$mail->AltBody = $mail->Body;
$mail->addAddress($email);

if ($mail->send()) {
    return "Password reset link sent to your email!";
} else {
    return "Failed to send password reset email. Error: " . $mail->ErrorInfo;
}


<?php

// Assuming you have a database connection established (e.g., $db)
// And a table named 'users' with columns 'id', 'email', 'password'

// Function to handle password reset request
function forgotPassword($email, $db) {
  // 1. Check if the email exists in the database
  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email); // "s" indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $resetToken = generateUniqueToken(); // Create a unique token
    $resetDate = date('Y-m-d H:i:s'); // Set the expiry date for the token

    // 2. Generate a unique token and store it in the database
    $query = "UPDATE users SET reset_token = ?, reset_date = ?, reset_flag = 1 WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $resetToken, $resetDate, $userId);
    $stmt->execute();

    // 3. Send password reset email
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . base_url() . "/reset-password?token=" . $resetToken; 
    $headers = "From: Your Website <noreply@yourwebsite.com>"; 

    $result = mail($email, $subject, $message, $headers);

    if ($result) {
      return true; // Success
    } else {
      return false; // Failure - email sending failed
    }
  } else {
    return false; // Email not found
  }
}


// Helper function to generate a unique token (e.g., using a random string)
function generateUniqueToken() {
  return bin2hex(random_bytes(32)); // Creates a 32-byte (256-bit) random string
}

// Example usage (within a form submission, after the user enters their email)
// $email = $_POST['email'];
// $db = $your_database_connection; //  Replace with your actual database connection

// if (isset($email)) {
//   if (forgotPassword($email, $db)) {
//     echo "Password reset email has been sent to $email.";
//   } else {
//     echo "Error: Could not send password reset email.  Check your email settings or database connection.";
//   }
// }
?>
