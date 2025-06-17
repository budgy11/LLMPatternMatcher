

<?php

// Assuming you have a database connection established
// and a user table with an 'email' column.

// Function to handle forgotten password requests
function forgotPassword($email) {
  // 1. Validate the email format (optional but recommended)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Check if the user exists
  $user = getUserByEmail($email); // Assuming you have a getUserByEmail function
  if (!$user) {
    return "User not found.";
  }

  // 3. Generate a unique token (using a library for security - see below)
  $token = generateToken();

  // 4. Store the token and user ID in the database
  // This is a simplified example - you'll likely want to use prepared statements
  // for security.
  //  - 'token' column in your user table to store the token.
  //  - 'reset_token_expiry' column to store the expiration time.
  resetPasswordToken($user['id'], $token, time() + 3600); // Expiry after 1 hour
  // You should use a prepared statement here!

  // 5. Send an email to the user with the reset link
  $subject = "Password Reset Request";
  $message = "Click the link below to reset your password: ";
  $link = base64_decode($token); // Decode the base64 encoded token
  $message .= "<a href='" . $link . "'>Reset Password</a>";

  $headers = "From: your_email@example.com"; // Replace with your email
  mail($email, $message, $headers);

  return "Password reset email sent to $email.";
}


// Dummy functions for demonstration - REPLACE WITH YOUR ACTUAL IMPLEMENTATION

// Function to retrieve a user by email
function getUserByEmail($email) {
  // Placeholder - replace with your database query
  // Example using PDO
  // $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  // $stmt->bindParam(':email', $email);
  // $stmt->execute();
  // $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // return $user;

  // Example return for demonstration purposes
  return [
    'id' => 1,
    'email' => 'test@example.com'
  ];
}


// Function to generate a unique token (use a cryptographically secure library)
function generateToken() {
  return bin2hex(random_bytes(32)); // Generates a 32-byte random string
}

// Function to store the reset token data in the database
function resetPasswordToken($userId, $token, $expiry) {
  // Replace with your database insertion query.
  // This is a simplified example, you should use prepared statements!
  // Example using PDO
  // $stmt = $pdo->prepare("INSERT INTO password_tokens (user_id, token, expiry) VALUES (:user_id, :token, :expiry)");
  // $stmt->bindParam(':user_id', $userId);
  // $stmt->bindParam(':token', $token);
  // $stmt->bindParam(':expiry', $expiry);
  // $stmt->execute();
}



// Example usage (for testing - NOT for production)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $result = forgotPassword($email);
  echo $result;
}
?>
