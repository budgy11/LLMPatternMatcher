

<?php

// Configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_pass = "your_database_password";

// Function to handle password reset requests
function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if User Exists
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  if ($conn->connect_error) {
    return "Database connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $user_email = $result->fetch_assoc()['email'];

    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use bin2hex for better security

    // 4. Hash the Token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 5. Update User's Record with Token
    $sql_update = "UPDATE users SET reset_token = '$hashed_token' WHERE id = '$user_id'";
    if ($conn->query($sql_update) === TRUE) {
      // 6. Send Password Reset Email
      $to = $email;
      $subject = "Password Reset Request";
      $message = "Click on the following link to reset your password: " . $_SERVER['PHP_SELF'] . "?token=" . $token;
      $headers = "From: your_email@example.com"; // Replace with your email

      mail($to, $subject, $message, $headers);

      return "Password reset email sent to $email.  Check your inbox.";
    } else {
      return "Error updating user record.";
    }
  } else {
    return "User with email '$email' not found.";
  }

  $conn->close();
}

// Example Usage (For testing - Don't expose this in a production environment)
// $reset_message = forgotPassword("test@example.com");
// echo $reset_message;

?>
