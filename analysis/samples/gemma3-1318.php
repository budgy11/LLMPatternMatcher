

<?php

// Assuming you have a database connection established in a file called 'db_config.php'
require_once 'db_config.php'; // Ensure this path is correct

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting the reset.
 * @return bool True if a reset link was successfully sent, false otherwise.
 */
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email); // Log the error
    return false;
  }

  // 2. Check if the user exists
  $user = mysqli_query($conn, "SELECT id, username FROM users WHERE email = '$email'");
  if (mysqli_num_rows($user) == 0) {
    error_log("User with email '$email' not found."); // Log the error
    return false;
  }

  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Use strong random bytes

  // 4. Hash the token (important for security!)
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 5. Store the token in the database
  $insert_query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ('" . mysqli_real_escape_string($conn, $user[0]['id']) . "', '$hashed_token', NOW() + INTERVAL 24 HOUR)"; // Store for 24 hours
  if (mysqli_query($conn, $insert_query)) {
    // 6. Send the reset link (implementation depends on your email sending method)
    $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
    //  You'd usually use a mail function or an email library here
    //  Example:
    //  mail($email, 'Password Reset Link', $reset_link);

    // Return true to indicate success
    return true;
  } else {
    error_log("Error inserting into password_resets table: " . mysqli_error($conn));
    return false;
  }
}


/**
 *  Example Usage (This is just an example; you'd integrate this into your login or user registration process)
 */

// Simulate a request (don't use this in a live application)
// $email = 'testuser@example.com';  // Replace with a valid email
// if (forgot_password($email)) {
//   echo "A password reset link has been sent to $email.";
// } else {
//   echo "Failed to send password reset link.";
// }


?>
