

<?php

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
  // 1. Validate Email (Important Security Step)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log invalid emails
    return false; // Or you could return true and continue to the email sending. 
                  //  Logging is crucial for security.
  }

  // 2. Retrieve User Information
  $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?"); //Use prepared statements for security!
  $stmt->bind_param("s", $email);
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    error_log("User with email " . $email . " not found.");
    return false; // User doesn't exist.
  }

  $user = $result->fetch_assoc(); // Fetch the user data
  $stmt->close();


  // 3. Generate a Unique Reset Token (Important for Security)
  $resetToken = bin2hex(random_bytes(32)); // Use a cryptographically secure random string

  // 4. Store the Token in the Database (Linked to the User)
  $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
  $stmt->bind_param("ss", $resetToken, time() + 3600); // Token expires in 1 hour (3600 seconds)
  $result = $stmt->execute();

  if (!$result) {
    error_log("Error updating user record with reset token: " . print_r($stmt->error, true));
    $stmt->close();
    return false;
  }

  $stmt->close();

  // 5. Send the Password Reset Email
  $subject = "Password Reset Request";
  $message = "Please click the following link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset_token=" . urlencode($resetToken);
  $headers = "From: Your Website <admin@yourwebsite.com>";

  if (mail($email, $subject, $message, $headers)) {
    return true; // Email sent successfully
  } else {
    error_log("Failed to send email to " . $email . " with reset token " . $resetToken);
    //  Consider adding a retry mechanism here to attempt sending the email again
    //  (e.g., after 5-10 minutes).
    return false;
  }
}


// Example Usage (This part would typically be part of a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (forgotPassword($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.  Please check your email and try again.";
    }
}
?>
