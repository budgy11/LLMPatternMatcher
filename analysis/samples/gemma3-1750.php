

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to reset password
function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generates a strong, random hexadecimal token

  // 3.  Store Token and User ID in Database
  //    (This should ideally be done in a dedicated "forgot_password" table)
  //    For simplicity, we'll store it in the users table.  
  //    **IMPORTANT:** In a real application, consider a separate table 
  //    for forgotten password tokens and expire them after a certain time.
  $query = "UPDATE users SET forgot_token = '$token', forgot_token_expiry = NOW() + INTERVAL 7 DAY WHERE email = '$email'"; // Expires after 7 days
  $result = mysqli_query($GLOBALS['host'], $query); 

  if (mysqli_error($GLOBALS['host'])) {
    return "Error generating reset link: " . mysqli_error($GLOBALS['host']);
  }

  // 4.  Send Password Reset Email
  $to = $email;
  $subject = "Password Reset";
  $message = "Please click the link below to reset your password:
" .
             "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>Reset Password</a>"; // Using $_SERVER['PHP_SELF']
  $headers = "From: your_email@example.com\r
";
  mail($to, $message, $headers);

  return "Password reset link sent to your email address.";
}


// Example Usage (This would typically be handled by a form submission)
//  Assuming you have a form that submits the email address.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $reset_message = forgotPassword($email);
    echo $reset_message;
}
?>
