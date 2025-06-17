

<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token, sends an email with a password reset link,
 * and deletes the token from the database.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, PDO $pdo) {
  // 1. Generate a unique token
  $token = bin2hex(random_bytes(32));  // Use cryptographically secure random bytes

  // 2.  Prepare and execute the query to insert the token into the database
  $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, NOW())");
  $stmt->execute([$email, $token]);

  // 3. Send the password reset email (replace with your email sending logic)
  $subject = "Password Reset Request";
  $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>here</a> to reset your password.";
  $headers = "From: your_email@example.com"; // Replace with your email address

  // Send the email
  if (!mail($email, $subject, $message, $headers)) {
    // Handle email sending failure.  Consider logging or error reporting.
    error_log("Failed to send password reset email to " . $email);
    return false;
  }

  // 4.  Log successful operation (optional, for auditing)
  error_log("Password reset email sent to " . $email . " with token: " . $token);


  return true;
}



// Example Usage (Illustrative - Adapt to your application)

// Connect to your database using a PDO connection
try {
    $host     = 'localhost';
    $dbname  = 'your_database_name';
    $user     = 'your_username';
    $password = 'your_password';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);

    // Enable PDO error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $email = 'user@example.com'; // Replace with the user's email
    if (forgotPassword($email, $pdo)) {
        echo "Password reset email sent to " . $email . ". Check your inbox!";
    } else {
        echo "Error sending password reset email.";
    }

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}


?>
