

<?php

// This file should be kept secure and not accessible directly from the web.
// Use it only within your application's logic.

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// 1. Get the email from the request (usually POST)
if (isset($_POST['email'])) {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Sanitize input

  // Check if email is valid
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // 2. Check if the email exists in the database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);

    try {
      $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        // 3. Generate a unique token and store it in the database
        $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token
        $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry  WHERE email = :email");
        $pdo->bindValue(':token', $token, PDO::PARAM_STR);
        $pdo->bindValue(':expiry', time() + 3600, PDO::PARAM_INT); // Token expires in 1 hour
        $pdo->bindValue(':email', $email, PDO::PARAM_STR);
        $pdo->execute();

        // 4. Send an email with the reset link
        $to = $email;
        $subject = 'Password Reset Link';
        $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . '?email=$email&token=$token'>Reset Password</a>";
        $headers = "From: your_email@example.com";  // Replace with your email

        mail($to, $message, $headers);


        echo "Password reset email has been sent. Please check your inbox.";

      } else {
        echo "User not found.";
      }

    } catch (PDOException $e) {
      echo "Database error: " . $e->getMessage();
    }
  } else {
    echo "Invalid email format.";
  }
}
?>
