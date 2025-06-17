    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// This is a simplified example and should be adapted to your specific environment
// and security needs.  It's crucial to implement robust security measures.

// 1.  Database Connection (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// 2.  Email Sending Function (Implement your email sending library here)
function send_email($email, $password) {
    // This is a placeholder.  Replace with your email sending logic
    // using a library like PHPMailer or SwiftMailer.
    // Example using a simple echo (DO NOT USE IN PRODUCTION)
    echo "Sending password reset email to: " . $email . "
";
    // In a real implementation, you'd send an email with a link to the
    // password reset form.

    // Example using PHPMailer (requires installation and configuration)
    //  require_once 'PHPMailer/PHPMailerAutoload.php';
    //  $mail = new PHPMailer();
    //  $mail->SMTPDebugEnable = true;  // Enable debugging
    //  $mail->isSMTP();
    //  $mail->Host       = 'smtp.example.com';
    //  $mail->Port = 587;
    //  $mail->SetOption('smtp-timeout', 60);
    //  $mail->Username = 'your_smtp_username';
    //  $mail->Password = 'your_smtp_password';
    //  $mail->SetFrom('youremail@example.com', 'Your Application Name');
    //  $mail->addRecipient($email, 'Your Application Name');
    //  $mail->Subject = 'Password Reset';
    //  $mail->Body = 'Click this link to reset your password: ' . $_SERVER['REQUEST_URI'];
    //  $mail->send();
}


// 3. Forgot Password Function
function forgot_password($email) {
    // Sanitize and Validate Input (Crucial for Security)
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the email exists in the users table (Replace 'users' with your table name)
    $query = "SELECT id, username FROM users WHERE email = '" . $email . "'";
    $result = mysqli_query($GLOBALS['db_host'], $query);

    if (mysqli_num_rows($result) > 0) {
        // Email exists, proceed with password reset
        $row = mysqli_fetch_assoc($result);
        send_email($email, "reset_token"); //  Pass a token or other identifier
        return "Password reset email sent to " . $email . ". Please check your inbox.";
    } else {
        return "Email address not found.";
    }
}

// 4. Example Usage (For demonstration only - don't expose this in a web app)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $reset_message = forgot_password($email);
    echo $reset_message;
}
?>
