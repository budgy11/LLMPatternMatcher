

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Fetch User Information
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $result = $conn->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $user_id = $user_data['id'];
    $user_email = $user_data['email'];

    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32));

    // 4.  Store Token in Database (For security - do not store passwords!)
    $conn->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    // 5.  Send Password Reset Email (See Email Sending Section Below)
    $reset_link = "http://yourwebsite.com/reset_password.php?token=$token&user_id=$user_id"; // Replace with your website URL
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password:
" . $reset_link;
    $headers = "From: yourname@yourdomain.com\r
";

    // Send Email (See Email Sending Section Below)
    //  $result = send_email($user_email, $subject, $message, $headers); // Uncomment if you have a send_email function

    return "Password reset link sent to your email address.";

  } else {
    return "User not found.";
  }

  $conn->close();
}



//-----------------------------------------------------------------------------------
//  Email Sending Function (Replace with your email sending logic)
//-----------------------------------------------------------------------------------

function send_email($to, $subject, $message, $headers) {
    // Implement your email sending code here.  This is a placeholder.

    // Example using PHPMailer (You'll need to install it: composer require phpmailer/phpmailer)
    // require_once 'vendor/phpmailer/phpmailer.php';

    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host   = 'smtp.gmail.com'; // or your SMTP server
    // $mail->Port = 587;
    // $mail->Username = 'your_gmail_username'; // Replace with your gmail username
    // $mail->Password = 'your_gmail_password'; // Replace with your gmail password
    // $mail->SetFrom('yourname@yourdomain.com', 'Your Name');
    // $mail->AddAddress($to, 'User Name');
    // $mail->Subject = $subject;
    // $mail->Body    = $message;
    // $mail->IsHTML(true);
    // $mail->Send();

    // return true; // or false if sending failed
}

//-----------------------------------------------------------------------------------
// Example Usage (For Testing)
//-----------------------------------------------------------------------------------

// Example:  Reset password for user with email 'test@example.com'
$result = forgot_password('test@example.com');
echo $result;

?>
