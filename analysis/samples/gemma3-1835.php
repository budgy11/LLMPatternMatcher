

<?php

// Assuming you have a database connection established
// $db = new PDO(...);  // Replace with your database connection

// **Important: Replace these with your actual values**
$table_name = 'users'; // Table name where user accounts are stored
$email_column = 'email';
$password_column = 'password';
$reset_token_column = 'reset_token';
$expiry_time = 60 * 60 * 24; // Expire reset token after 24 hours (seconds)
$secret_key = 'your_secret_key_here'; // A strong, random secret key

/**
 * Generates a unique reset token.
 *
 * @return string Unique reset token.
 */
function generateUniqueToken() {
  return bin2hex(random_bytes(32));
}


/**
 * Creates a reset token for a user.
 *
 * @param PDO $db  Database connection.
 * @param string $email  User's email.
 * @return bool True on success, false on failure.
 */
function createResetToken(PDO $db, string $email) {
  $token = generateUniqueToken();

  $stmt = $db->prepare("SELECT id FROM {$table_name} WHERE {$email_column} = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_id) {
    $stmt = $db->prepare("UPDATE {$table_name} SET {$reset_token_column} = :token, expiry_time = :expiry_time WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id['id'], PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiry_time', $expiry_time, PDO::PARAM_INT);
    return $stmt->execute();
  } else {
    return false; // User not found
  }
}


/**
 * Resets the password for a given email and token.
 *
 * @param PDO $db Database connection.
 * @param string $email User's email.
 * @param string $token Token.
 * @return bool True on success, false on failure.
 */
function resetPassword(PDO $db, string $email, string $token) {
  $stmt = $db->prepare("SELECT id FROM {$table_name} WHERE {$email_column} = :email AND {$reset_token_column} = :token");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':token', $token, PDO::PARAM_STR);
  $stmt->execute();

  $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_id) {
    // Generate a new password (for demonstration - use a proper password generation method in production)
    $new_password = 'password123';  // **IMPORTANT:  This is just an example!  Never use this in production.**

    $stmt = $db->prepare("UPDATE {$table_name} SET {$password_column} = :password, {$reset_token_column} = '', expiry_time = '' WHERE id = :user_id");
    $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id['id'], PDO::PARAM_INT);
    return $stmt->execute();
  } else {
    return false; // Token or user not found
  }
}


/**
 * Example Usage (For demonstration purposes only - handle this in a real application)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"];

  // Create a reset token if one doesn't exist. This is the trigger to start the process.
  if (!createResetToken($db, $email)) {
    echo "<p>Failed to generate reset token.</p>";
  } else {
    //Reset Password
    if (resetPassword($db, $email, $reset_token)) {
      echo "<p>Password reset successful!  Please check your email.</p>";
    } else {
      echo "<p>Invalid reset token or user not found.</p>";
    }
  }
}

?>
