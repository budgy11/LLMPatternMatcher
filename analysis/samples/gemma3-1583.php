

<?php

// Database connection details (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2.  Check if the user exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    return 'Database connection failed: ' . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $userId = $user_data['id'];
    $username = $user_data['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 4. Store the token in the database
    $sql = "UPDATE users SET password_token = '$token' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
      // 5. Send the reset link (implementation depends on your email sending method)
      //  This part requires a mail function or an email sending library.
      //  Example:
      $resetLink = 'http://yourwebsite.com/reset-password?token=' . $token;
      //  You would need to send an email to the user's email address
      //  with the resetLink in the body.
      
      // Return a success message with the reset link
      return '<a href="' . $resetLink . '" >Click here to reset your password</a>';

    } else {
      return 'Error updating user data.';
    }
  } else {
    return 'User not found.';
  }

  $conn->close();
}

// Example Usage (for testing, you should handle this through a form)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Check if a token is provided. This is how you'd typically get the token
  // from the URL in the reset-password page.
  if (isset($_GET['token'])) {
      $token = $_GET['token'];
      $resetPasswordMessage = forgotPassword($token);
      echo $resetPasswordMessage; // Output the returned message.  Replace with your display logic.
  } else {
     echo "No token provided.";
  }

}
?>
