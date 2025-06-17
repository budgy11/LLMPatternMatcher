

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle password reset request
function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Check if the user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Database connection failed: " . $conn->connect_error;
  }

  $result = $conn->query("SELECT id, username FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); 

    // 4. Store the token in the database for the user
    $conn->query("UPDATE users SET reset_token = '$token' WHERE id = '$userId'");

    // 5.  Create a reset link (Email Content)
    $subject = "Password Reset Request";
    $to = $email;
    $message = "Dear " . $username . ",

" .
               "You requested a password reset.
" .
               "Please click the following link to reset your password:
" .
               "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>Reset Password</a>

" .
               "This link expires in 1 hour.

" .
               "Sincerely,
The Password Reset Team";

    // 6. Send the email (using mail() - adjust your server's configuration)
    if (mail($to, $subject, $message)) {
      return "Password reset email sent to $email.";
    } else {
      return "Password reset email failed to send. Check your server's mail configuration.";
    }
  } else {
    return "User not found with email $email.";
  }

  $conn->close();
}

// Example Usage (This is just for demonstration -  implement this in your form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetResult = forgotPassword($email);
  echo "<p>" . $resetResult . "</p>";
}
?>
