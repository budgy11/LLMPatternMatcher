

<?php

/**
 * Forgotten Password Function
 * 
 * This function handles the logic for resetting a user's password.
 * It includes generating a unique token, storing it in the database,
 * sending an email with a link to reset the password, and validating the token.
 *
 * @param string $email The email address of the user requesting password reset.
 * @return bool True if the password reset process started successfully, false otherwise.
 */
function forgot_password($email)
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32));  // Use a strong random token

  // 3. Hash the Token (Important for Security)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4. Store the Token in the Database
  // Assuming you have a 'users' table with 'email' and 'password' columns
  // Replace with your actual database connection and table names.
  $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); 

  $stmt = $db_connection->prepare("
      INSERT INTO forgot_passwords (email, token, hashed_token, created_at)
      VALUES (:email, :token, :hashed_token, NOW())
  ");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':token', $token);
  $stmt->bindParam(':hashed_token', $hashed_token);
  $stmt->execute();

  // 5. Send Password Reset Email
  $subject = 'Password Reset Request';
  $message = "Click on the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '"' . ' target="_blank">' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '</a>'; 

  $headers = "From: your_email@example.com" . "\r
"; //Replace with your email
  mail($email, $subject, $message, $headers);  // Use PHP's mail function

  return true; // Password reset process initiated
}


/**
 * Validates the password reset token
 *
 * @param string $token The password reset token.
 * @return bool True if the token is valid and hasn't expired, false otherwise.
 */
function validate_token($token)
{
    // 1. Retrieve the token from the database.
    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password");
    $stmt = $db_connection->prepare("SELECT id, email, token, hashed_token, created_at FROM forgot_passwords WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $forgot_password = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$forgot_password) {
        return false; // Token not found
    }

    // 2. Check if the token has expired.  (Important!)
    $expiration_time = time() - 3600; // Expires after 1 hour (3600 seconds)
    if ($forgot_password['created_at'] > $expiration_time) {
        return false; // Token has expired
    }

    // 3. Verify the hashed token
    return password_verify($token, $forgot_password['hashed_token']);
}


// Example Usage (Demonstration - Adapt to your app)
// $email = "testuser@example.com"; // Replace with the user's email
// if (forgot_password($email)) {
//     echo "Password reset email has been sent to " . $email . ".";
// } else {
//     echo "Failed to initiate password reset.";
// }


?>
