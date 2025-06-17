  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php
require_once 'PHPMailer/PHPMailerAutoload.php'; // Assuming PHPMailer is in the same directory

// ... other code ...

// Send email with PHPMailer
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.example.com'; // Replace with your SMTP server
$mail->Port = 587; // Or 465 for SSL
$mail->SMTPAuth = true;
$mail->Username = 'your_smtp_username';
$mail->Password = 'your_smtp_password';
$mail->SetFrom('yourwebsite@example.com', 'Your Website');
$mail->Subject = 'Password Reset Request';
$mail->Body    = $message;
$mail->AltBody = $message; // For non-HTML clients
$mail->AddAttachment('attachment.pdf');  // Optional

if ($mail->send()) {
    // Email sent successfully
} else {
    // Email sending failed
}
?>
