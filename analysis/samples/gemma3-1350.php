        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your email address" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password(string $email) {
  // Sanitize the email input (important for security)
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log for debugging
    return false;
  }

  // Check if the user exists
  $user = getUserByEmail($email);

  if (!$user) {
    error_log("User with email " . $email . " not found."); // Log for debugging
    return false;
  }


  // Generate a unique token (important for security - use a strong random string generator)
  $token = generate_unique_token();

  // Store the token and expiration date in the database
  $result = save_token_for_user($user->id, $token, date('Y-m-d H:i:s', strtotime('+30 days'))); // Expires in 30 days

  if (!$result) {
    error_log("Failed to save token for user " . $email); // Log for debugging
    return false;
  }

  // Send the password reset email
  $subject = "Password Reset Request";
  $message = "Please click on the following link to reset your password:
" .
             "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
  $headers = "From: Your Website <noreply@yourwebsite.com>\r
";  // Replace with your actual email address

  if (mail($email, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send email to " . $email); // Log for debugging
    // Optionally, delete the token if the email fails to send (important for security)
    delete_token_for_user($user->id);
    return false;
  }
}


/**
 * Example helper functions (replace with your actual implementations)
 */

// Dummy function to retrieve user by email
function getUserByEmail(string $email) {
  // Replace with your actual database query
  // This is just a placeholder.  You MUST implement this.
  // Example:
  // $db->query("SELECT * FROM users WHERE email = '$email'");
  // $row = $db->fetch_assoc();
  // if ($row) {
  //   return new User($row); // Create a User object
  // } else {
  //   return null;
  // }

  //Placeholder return for testing
  return new User(['id' => 1, 'email' => 'test@example.com']);
}


// Dummy function to generate a unique token.
//  Use a better method in production (e.g., random_bytes, md5 with salt).
function generate_unique_token() {
  return bin2hex(random_bytes(32)); //  Using bin2hex for simplicity here.  Securely generate random strings for production.
}



// Dummy function to save the token and expiration date in the database.
function save_token_for_user(int $userId, string $token, string $expirationDate) {
  // Replace with your actual database query to store the token and expiration date.
  // Example:
  // $db->query("UPDATE users SET reset_token = '$token', reset_date = '$expirationDate' WHERE id = $userId");
  // return $db->affected_rows > 0 ? true : false;

  //Placeholder return for testing
  return true;
}

// Dummy function to delete the token from the database.
function delete_token_for_user(int $userId) {
  // Replace with your actual database query to delete the token.
  // Example:
  // $db->query("DELETE FROM users WHERE id = $userId AND reset_token != ''");
  // return $db->affected_rows > 0 ? true : false;

  //Placeholder return for testing
  return true;
}



//  Example Usage (For testing)
if (isset($_GET['reset'])) {
  $token = $_GET['reset'];
  if (isset($token)) {
    if (forgot_password($token)) {
      echo "Password reset email sent successfully!";
    } else {
      echo "Failed to send password reset email.";
    }
  } else {
    echo "Invalid reset token.";
  }
}

?>
