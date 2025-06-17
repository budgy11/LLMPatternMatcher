

<?php

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

// Function to handle password reset requests
function forgotPassword($email, $db) {
  // 1. Validate Email
  $email = trim($email); // Remove leading/trailing whitespace
  if (empty($email)) {
    return "Error: Please enter your email address.";
  }
  
  // Check if the email exists in the database
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");
  if ($result->num_rows == 0) {
    return "Error: Email address not found.";
  }

  $user = $result->fetch_assoc(); // Fetch the user data
  $userId = $user['id'];
  $userEmail = $user['email'];

  // 2. Generate a unique, secure token (e.g., using random_v4)
  $token = bin2hex(random_bytes(32));

  // 3.  Store the token in the database, associated with the user.
  $sql = "UPDATE users SET password_reset_token = '$token' WHERE id = $userId";
  if (!$db->query($sql)) {
    return "Error: Failed to generate password reset token.";
  }

  // 4.  Send an email with a link containing the token
  $subject = "Password Reset Request";
  $message = "Click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?token=$token'>" . $_SERVER['PHP_SELF'] . "?token=$token</a>";  // IMPORTANT:  Use the same script name
  $headers = "From: your_email@example.com"; // Replace with your email address

  if (mail($email, $subject, $message, $headers)) {
    return "Password reset email has been sent to $email.";
  } else {
    return "Error: Failed to send password reset email.";
  }
}


// Example Usage (within a form or other context)

// Assuming you have a form that captures the email address
// and calls this function when submitted.

// Get the email from the form (e.g., from $_POST)
// $email = $_POST['email'];  // This is just an example.  Replace with your form input.

// Call the function to generate the reset link
// $result = forgotPassword($email, $db);
// echo $result;


// *** SECURITY NOTES & IMPORTANT CONSIDERATIONS ***

// 1.  CSRF Protection:  You MUST implement CSRF (Cross-Site Request Forgery) protection.
//     This prevents attackers from submitting a malicious form on your behalf.
//     Use a token-based approach (e.g., a hidden input field with a random, unique value)
//     and verify it on the server-side.

// 2.  Password Reset Link Expiration:  Set a short expiration time (e.g., 30 minutes) for the password reset link.  This is crucial for security.
//     You can achieve this by adding a 'expiration_time' column to the 'users' table and updating it when the token is created.
//     When the user clicks the link, check if the token is still valid before allowing them to set a new password.

// 3. Token Generation: Use `random_bytes()` to generate a truly random token.  Don't use predictable algorithms.  The `bin2hex()` function is used to convert the binary data into a hexadecimal string.

// 4.  Database Security:  Protect your database connection credentials.  Don't hardcode them into your script.  Use environment variables or a configuration file.  Sanitize and validate all user input to prevent SQL injection vulnerabilities.

// 5. Logging:  Log all password reset requests (including the email address and the token used) for auditing and security monitoring purposes.

// 6.  The `$_SERVER['PHP_SELF']` is used to create a relative link that will work correctly even if the script is moved. However, be mindful that this relies on the current script's name.

// 7.  Avoid storing passwords in plain text.  Always hash passwords before storing them in the database.  Use a strong hashing algorithm like bcrypt or Argon2.  This example assumes you have an existing secure password hashing mechanism.

?>
