

<?php

// Assume you have a database connection established and available as $db

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email (Crucial for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists in the database
  $stmt = $db->prepare("SELECT id, password, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "User not found. Please ensure you entered the correct email address.";
  }

  // 3. Generate a Unique Token (Important for security)
  $token = bin2hex(random_bytes(32));  // Use a cryptographically secure random source.

  // 4. Update the user's record with the token
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_expiry = ? WHERE email = ?");
  $stmt->execute([$token, time() + 3600, $email]); // expiry time 1 hour, adjust as needed

  // 5.  Send an Email with the Token & Reset Link
  $to = $email;
  $subject = "Password Reset Link";
  $message = "Click this link to reset your password: " .  $_SERVER['REQUEST_URI'] . "?token=" . $token;
  $headers = "From: your_website@example.com\r
"; //Replace with your email
  mail($to, $message, $headers);

  return "Please check your email to reset your password.";
}


// Example Usage (This would be part of your form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if ($email) {
    $result = forgot_password($email);
    echo "<p>" . $result . "</p>";
  } else {
    echo "<p>Please enter your email address.</p>";
  }
}

?>
