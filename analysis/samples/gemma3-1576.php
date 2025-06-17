  <form action="reset_password.php" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>
  <?php
}

// 2. Reset Password Function (reset_password.php - this is called after form submission)
function resetPassword($email) {
  // IMPORTANT:  This is a simplified example.  In a real-world scenario,
  // you would implement more robust security measures such as:
  //  - Sending a temporary, unique, and time-limited token via email.
  //  - Verifying the token against the database.
  //  - Using secure password hashing algorithms (e.g., bcrypt, Argon2).

  // 1.  Check if the email exists in the database
  $query = "SELECT id, username FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email);  // "s" for string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // 2. Generate a temporary password reset token (for demonstration only!)
    $token = bin2hex(random_bytes(32));

    // 3. Store the token in the database (associate it with the user)
    $query = "UPDATE users SET reset_token = ? WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    // 4.  Email the user with a link containing the token
    $subject = "Password Reset Request";
    $message = "Click this link to reset your password: " .  $_SERVER['PHP_SELF'] . "?token=" . $token;
    $headers = "From: your_email@example.com" . "\r
";
    mail($email, $subject, $message, $headers);

    echo "Password reset email has been sent to " . $email;

  } else {
    echo "Email not found.";
  }

  $stmt->close();
}



// Example Usage (This would be triggered by a form submission)

// Assuming you received the email from the form
if (isset($_POST['email'])) {
  $email = $_POST['email'];
  resetPassword($email);
}
?>
