
<!-- HTML Form for triggering the reset (example only) -->
<form method="post" action="">
  <label for="reset_email">Enter Email Address:</label>
  <input type="text" id="reset_email" name="reset_email">
  <button type="submit">Reset Password</button>
</form>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle password reset requests
function forgotPassword($email) {
  // 1. Validate Email (Basic Check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $hashedToken = generateResetToken($userId); // Generate a unique token
    $resetLink = generateResetLink($userId, $hashedToken); // Create the reset link

    // 3. Store the token in the database
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($userId, '$hashedToken', NOW())";
    if ($conn->query($sql) === TRUE) {
      // 4. Send Password Reset Email
      $to = $email;
      $subject = "Password Reset";
      $message = "Click on the following link to reset your password: " . $resetLink;
      $headers = "From: your_email@example.com" . "\r
";

      if (mail($to, $subject, $message, $headers)) {
        return "Password reset email sent to $email.  Check your inbox.";
      } else {
        return "Password reset email failed to send.  Check your server logs.";
      }

    } else {
      return "Error inserting token into database: " . $conn->error;
    }
  } else {
    return "User with email $email not found.";
  }

  // Close the connection
  $conn->close();
}


// Helper Functions (Implement these)

// Function to generate a unique token (e.g., using UUID)
function generateResetToken() {
  return bin2hex(random_bytes(32)); // Generate a 32-byte random string
}

// Function to generate the reset link
function generateResetLink($userId, $token) {
  return "/reset_password?userId=$userId&token=$token";  // Adjust the URL accordingly
}



// Example Usage (for testing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $response = forgotPassword($email);
  echo "<p>" . $response . "</p>";
}
?>
