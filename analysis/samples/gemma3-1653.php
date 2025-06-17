

<?php

// **Important Security Considerations:**
// - This is a simplified example for demonstration purposes ONLY.
// - In a production environment, you *absolutely must* implement robust security measures
//   including:
//   - **Strong Password Hashing:** Use bcrypt or Argon2 to hash passwords securely. Never store passwords in plain text.
//   - **Rate Limiting:**  Prevent brute-force attacks by limiting the number of password reset attempts.
//   - **Email Verification:**  Always verify that the email address provided is valid and belongs to the user.
//   - **Session Security:**  Secure your session management to prevent unauthorized access.
//   - **HTTPS:**  Always use HTTPS to encrypt all communication between the user and your server.
// - Consider using a password reset library or framework components for enhanced security.

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the database
  $result = $db->query("SELECT id, password FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    // 3. Generate a Unique Token (for security)
    $token = bin2hex(random_bytes(32)); // Use a strong random number generator

    // 4.  Create a Reset Token Record in the Database
    $db->query("INSERT INTO password_resets (user_id, token, expires_at) VALUES ($result->id, '$token', NOW() + INTERVAL 24 HOUR)");

    // 5. Send an Email with a Reset Link
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>"; // Construct the reset link
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);

    return "Password reset email sent to $email.";

  } else {
    return "Email not found.";
  }
}

// **Example Usage (within a form or script):**

// Get the email address from the form
// $email = $_POST['email'];

// Call the forgot_password function
// $result = forgot_password($email, $db);

// Display the result (or redirect to a page)
// echo $result;

// **Important:  You'll need to implement the password reset link handling (checking the token and updating the password)
// separately. This is a simplified example only.**
?>
