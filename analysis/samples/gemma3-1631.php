        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)
// and a 'users' table with 'email' and 'password' columns.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Check if the email exists
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?"); // Replace $db with your database connection
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    // Email doesn't exist
    return false;
  }

  // 2. Generate a unique token/code
  $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator

  // 3.  Store the token in the database, associated with the user's ID.
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE id = ?");
  $stmt->execute([$token, time() + 3600, $user['id']]); // Expire the token after 1 hour.  Adjust as needed.

  // 4. Send the reset link email (this part is *crucial*)
  $resetLink = "http://yourwebsite.com/reset-password?token=$token"; // Replace with your domain
  $to = $user['email'];
  $subject = "Password Reset Request";
  $message = "Please click on the following link to reset your password: " . $resetLink;
  $headers = "From: yourwebsite@example.com";  // Replace with your email address

  mail($to, $subject, $message, $headers);

  return true;
}


// Example Usage (In a form submission or other event):
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (empty($email)) {
    // Handle empty email input
    echo "Please enter your email address.";
  } else {
    if (forgotPassword($email)) {
      echo "Password reset email has been sent to $email.";
    } else {
      echo "Password reset failed.  Perhaps that email address doesn't exist.";
    }
  }
}


// HTML Form (example - for demonstration purposes only):
?>
