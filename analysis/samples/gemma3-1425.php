

<?php

// Assuming you have a database connection established (e.g., $pdo)
// and a table with user credentials (e.g., 'users')

function forgotPassword($email, $pdo) {
  // 1. Check if the email exists in the database
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 3. Store the token in the database associated with the user
  $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_expires = :expires WHERE email = :email");
  $stmt->bindParam(':token', $token);
  $stmt->bindParam(':expires', time() + 3600); // Token expires in 1 hour (3600 seconds)
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  // 4. Return the token (you'll need to send this to the user)
  return $token;
}


// Example usage:
// (Assume you have a form where a user enters their email)
$email = $_POST['email'];  // Get email from the form

if (isset($email)) {
  $token = forgotPassword($email, $pdo);

  if ($token) {
    // Send an email to the user containing the token and a link
    $subject = "Password Reset Link";
    $to = $email;
    $message = "Please click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com" . "\r
";

    mail($to, $message, $headers);

    echo "<p>Password reset link has been sent to your email address.</p>";
    //  IMPORTANT:  You'll likely want to also redirect the user
    //  to a page or display a message that the link is sent.
  } else {
    echo "<p>An error occurred while generating the password reset token.</p>";
  }
} else {
  echo "<p>Please enter your email address.</p>";
}


//  ======================================================================
//  Reset Password Function (for handling the reset link)
//  ======================================================================

function resetPassword($token, $password, $userId, $pdo) {
    // 1. Check if the token exists
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE password_reset_token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // Invalid token
    }

    // 2. Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 3. Update the user's password
    $stmt = $pdo->prepare("UPDATE users SET password = :password, password_reset_token = NULL, password_reset_expires = NULL WHERE id = :userId AND email = :email");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':userId', $user['id']);
    $stmt->bindParam(':email', $user['email']);
    $stmt->execute();

    return true;
}



// Example usage to reset the password:

// (Assume you have a form where the user enters the token and the new password)

if (isset($_GET['reset'])) {
    $token = $_GET['reset'];
    $password = $_POST['password']; // Get password from the form

    if (resetPassword($token, $password, $userId, $pdo)) {
      echo "<p>Password reset successfully.</p>";
    } else {
      echo "<p>Invalid token or password.</p>";
    }
}



?>
