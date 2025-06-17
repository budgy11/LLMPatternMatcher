

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Check if the user exists in the database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $userEmail = $row['email'];

    // 3. Generate a unique, secure token
    $token = bin2hex(random_bytes(32)); // Generate a 32-byte random string
    
    // 4.  Store the token in the database -  use a 'temp_password_token' column
    $insertQuery = "UPDATE users SET temp_password_token = '$token' WHERE id = '$userId'";
    if (!$conn->query($insertQuery)) {
      return "Error updating database.  " . $conn->error;
    }

    // 5.  Send an email to the user with the token and a link
    $to = $email;
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password:
" .
               "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>Reset Password</a>"; // Use $_SERVER['PHP_SELF']
    $headers = "From: your_email@example.com"; // Replace with your email

    if (mail($to, $subject, $message, $headers)) {
      return "Password reset email sent to $email.  Please check your inbox.";
    } else {
      return "Failed to send password reset email. Check your server's mail configuration.";
    }
  } else {
    return "User not found.";
  }
}

// Example usage (from the reset.php page)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetToken = $_POST["reset_token"];

  if (empty($email) || empty($reset_token)) {
    echo "Error: Both email and token are required.";
  } else {
    $result = forgotPassword($email, $reset_token);
    echo $result; // Display the result
  }
}
?>
