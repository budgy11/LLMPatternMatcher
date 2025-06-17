
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// **Important Security Notes:**
// * This code is a basic example and should NOT be used in a production environment without significant security enhancements.
// * NEVER store passwords directly in the database.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.
// * This example uses a simple email verification. For a real application, you'd want to use a more robust system.
// * Consider using a dedicated password reset service for better security and user experience.
// * Implement rate limiting and anti-abuse measures to prevent brute-force attacks.

// Configuration
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';
$from_email = 'your_email@example.com'; // Sender email address
$website_url = 'https://yourwebsite.com'; // Website URL

// Function to reset the password
function forgot_password($email, $website_url) {
  // 1. Database Connection (Replace with your actual database connection)
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

  // 3. Store the token in the database (associate with the email)
  $sql = "INSERT INTO forgot_password_tokens (email, token, expiry_timestamp)
          VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)"; // Expires after 24 hours

  if ($conn->query($sql) === FALSE) {
    echo "Error inserting token: " . $conn->error;
    return false;
  }

  // 4.  Create and send the password reset email
  $subject = 'Password Reset Request';
  $message = "Hello,

" .
             "You have requested a password reset for your account.

" .
             "Please click on the following link to reset your password:
" .
             "<a href='" . $website_url . "/reset_password.php?token=$token'>Reset Password</a>

" .
             "If you did not request this password reset, please ignore this email.

" .
             "Sincerely,
" .
             $website_url;

  $headers = "From: " . $from_email . "\r
";
  $headers .= "Reply-To: " . $from_email . "\r
";

  if (!mail($email, $subject, $message, $headers)) {
    echo "Error sending email.
";
    // Attempt to delete the token in case email sending failed.  This is crucial.
    $delete_sql = "DELETE FROM forgot_password_tokens WHERE email = '$email'";
    if ($conn->query($delete_sql) === FALSE) {
        echo "Error deleting token: " . $conn->error;
    }
    return false;
  }

  return true;
}

// Example Usage (Simulated Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Sanitize email

  if (empty($email)) {
    echo "Please enter your email address.";
  } else {
    if (forgot_password($email, $website_url)) {
      echo "Password reset email sent to " . $email;
    } else {
      echo "Password reset failed.";
    }
  }
}

?>
