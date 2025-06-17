
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

// Database configuration (Replace with your actual values)
$db_host = 'localhost';
$db_user = 'your_username';
$db_pass = 'your_password';
$db_name = 'your_database_name';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);  // Sanitize input
  if(empty($email)){
    return "Invalid email address.";
  }


  // 2.  Check if User Exists
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $user_email = $result->fetch_assoc()['email'];

    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

    // 4.  Store the Token in the Database (Temporary)
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($user_id, '$token', NOW() + INTERVAL 24 HOUR)"; // Expires after 24 hours

    if ($conn->query($sql) === TRUE) {
      // 5.  Send the Reset Link (Email) -  Implement this part
      $to = $email;
      $subject = 'Password Reset Link';
      $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";  // Use PHP_SELF to ensure correct link
      $headers = "From: your_email@example.com"; // Replace with your email
      mail($to, $message, $headers);

      return "Password reset link sent to your email. Please check your inbox.";
    } else {
      return "Error inserting token into database: " . $conn->error;
    }
  } else {
    return "User not found with email: $email";
  }

  $conn->close();
}

// Example Usage (for testing - typically handled through a form)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET['reset'])) {
    $reset_token = $_GET['reset'];
    $result = forgot_password($reset_token);
    echo $result;
  } else {
     echo "Enter email to reset your password.";
  }
}
?>
