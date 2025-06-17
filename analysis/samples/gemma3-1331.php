
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

// Replace with your database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASSWORD', 'your_db_password');
define('DB_NAME', 'your_db_name');

// Function to reset password
function reset_password($email) {
  // 1. Connect to the database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 3. Prepare the SQL query to set the token
  $sql = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";

  // 4. Execute the query
  if ($conn->query($sql) === TRUE) {
    // 5. Send a reset password email (Example - needs to be adapted to your email sending logic)
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    echo "Password reset link has been sent to $email. Please check your inbox.";
  } else {
    echo "Error resetting password. Query failed: " . $conn->error;
  }

  // 6. Close the connection
  $conn->close();
}

// Example usage (This part is for testing, you'll handle this through your form)
// If you need to test it without a form, you can call it like this:
// reset_password("testuser@example.com"); // Replace with a valid email
?>
