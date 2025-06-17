

<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with an 'email' and 'password' column.

function forgotPassword($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if user exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userEmail = $user['email'];

    // 3. Generate a Unique Token (e.g., using uuid_v4)
    $token = bin2hex(random_bytes(32));  // Generate a cryptographically secure random token

    // 4. Hash the Password (Important for Security)
    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT); // Using PHP's password_hash function

    // 5. Store the Token & User ID in the Database
    $db->query("UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() WHERE id = '$userId'");

    // 6. Prepare a Password Reset Email
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>Reset Password</a>";
    $headers = "From: your_email@example.com"; // Replace with your email

    mail($to, $message, $headers);


    return 'Password reset email sent to ' . $email;

  } else {
    return 'User not found.';
  }
}


// Example Usage (This is just for demonstration and should be integrated with a form)
// You'd typically call this function from a form after a user enters their email.
// The following code demonstrates the usage, but it's not a complete form.

// $email = $_POST['email']; // Get email from form input

// if (isset($email)) {
//   $result = forgotPassword($email);
//   echo $result;
// } else {
//   echo "Please enter your email address.";
// }



// ---------------------  IMPORTANT SECURITY CONSIDERATIONS ---------------------

// 1.  **Never store plain text passwords.** Always hash passwords using a strong hashing algorithm like `password_hash()` with `PASSWORD_DEFAULT`.

// 2. **Token Expiry:**  The `reset_token_expiry` column in the database sets an expiration time for the token. This is crucial for security.  When the user clicks the reset link, the token will expire, preventing unauthorized access.

// 3. **Token Uniqueness:** `bin2hex(random_bytes(32))` generates a cryptographically secure random token, ensuring uniqueness.

// 4.  **Rate Limiting:** Implement rate limiting to prevent brute-force attacks where attackers try to reset passwords repeatedly.

// 5. **Input Validation:** Thoroughly validate all user input (email address, token, etc.) to prevent vulnerabilities. Use `filter_var()` and other validation functions.

// 6. **HTTPS:**  Always use HTTPS to encrypt communication between the user's browser and your server, protecting the token during transmission.

// 7. **Logging:** Implement logging to track password reset attempts, which can help detect suspicious activity.
?>
