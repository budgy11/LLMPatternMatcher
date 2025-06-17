

<?php

// Assuming you have a database connection established (e.g., $db)
// and you have a table 'users' with columns like 'id', 'email', and 'password_hash'

// Function to handle forgotten password requests
function forgot_password($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "User with this email does not exist.";
  }

  // 3. Generate a unique token and store it in the database
  $token = bin2hex(random_bytes(32));  // Generate a random token
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())");
  $stmt->execute([$user['id'], $token]);

  // 4. Send an email with a link to reset the password
  $reset_link = "https://yourwebsite.com/reset_password?token=" . urlencode($token);
  $subject = "Password Reset Link";
  $message = "Click the link above to reset your password: " . $reset_link;
  $headers = "From: Your Website <noreply@yourwebsite.com>";
  mail($email, $subject, $message, $headers);

  return "Password reset email sent to $email.";
}


// Example usage (This part is just for demonstration)
// In a real application, this would be handled by a form submission
// and would be part of a larger application logic.

//  Assume $email is the email address provided by the user
//  $reset_result = forgot_password($email);
//  echo $reset_result;
?>
