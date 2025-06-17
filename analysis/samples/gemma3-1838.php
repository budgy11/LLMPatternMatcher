

<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect('host', 'username', 'password', 'database'));

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return string  A message indicating success or failure.
 */
function forgotPassword(string $email): string
{
    // 1. Validate Input (Important!)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // 2. Check if the user exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $stmt->close(); // Close the query

        // 3. Generate a unique reset token
        $resetToken = bin2hex(random_bytes(32)); // Generate a cryptographically secure token
        $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $resetToken, time() + 3600); // Expires in 1 hour.  Adjust as needed.
        $stmt->execute();
        $stmt->close();

        // 4. Send the reset password link (Implementation depends on your email setup)
        $resetLink = "https://yourwebsite.com/reset_password?token=$resetToken"; // Replace with your actual domain
        // You'll likely need to implement code here to send an email
        // with this link. Example:
        // sendEmail( $email, "Reset Password", "Click this link to reset your password: $resetLink");


        return "Password reset link sent to your email address. Please check your inbox.";

    } else {
        return "User not found.";
    }
}


// Example Usage (Illustrative - Replace with your actual logic)
// $email = "testuser@example.com";
// $result = forgotPassword($email);
// echo $result;


// Placeholder for sendEmail function (You'll need to implement this)
/**
 * Sends an email.
 * @param string $to
 * @param string $subject
 * @param string $message
 */
function sendEmail(string $to, string $subject, string $message) {
  // TODO: Implement your email sending logic here (using PHPMailer, etc.)
  // Example:
  // $mail = new PHPMailer(true);
  // $mail->SetFrom("your_email@example.com", "Your Website");
  // $mail->AddAddress($to, "User Name");  // Get user name from database
  // $mail->Subject = $subject;
  // $mail->MsgBody = $message, 'html'); // or 'text'
  // $mail->send();
}

?>
