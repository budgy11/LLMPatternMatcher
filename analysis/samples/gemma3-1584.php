

<?php

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Function to handle password reset
function forgotPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Check if the email exists in the database
  $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "User with this email does not exist.";
  }

  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 4. Store the token in the database
  $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
  $stmt->bindParam(':user_id', $user['id']);
  $stmt->bindParam(':token', $token);
  $stmt->bindParam(':expires_at', date('Y-m-d H:i:s', strtotime('+24 hours'))); // Expires in 24 hours
  $stmt->execute();

  // 5. Send the reset email
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click on this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourwebsite.com/reset?token=" . $token; // Replace yourwebsite.com
  $headers = "From: yourname@yourwebsite.com";

  mail($to, $message, $headers);

  return "Password reset email sent to $email.";
}


// Example Usage (This part is for testing - you'll need a form to submit the email)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetMessage = forgotPassword($email);
  echo "<p>" . $resetMessage . "</p>";
}
?>
