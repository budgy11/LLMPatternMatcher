

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// --- Function to reset password ---
function resetPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $username = $row['username'];

    // 3. Generate a unique token (important for security)
    $token = bin2hex(random_bytes(32));

    // 4.  Store the token and expiration time in the database
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)"; // Expires in 7 days
    if (!$conn->query($sql)) {
      return "Error inserting reset token: " . $conn->error;
    }


    // 5. Email the user with the reset link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: <a href='" . $_SERVER["PHP_SELF"] . "?reset=$token'</a>";
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
      return "Password reset email sent to $email.";
    } else {
      return "Failed to send password reset email.";
    }
  } else {
    return "User with email $email not found.";
  }

  $conn->close();
}



// --- Example Usage (for testing - DO NOT include this in a production environment) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetMessage = resetPassword($email);
  echo "<br>" . $resetMessage;
}
?>
