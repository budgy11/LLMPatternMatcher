

<?php

// Database connection details (replace with your actual details)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_database_name';

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword($email) {
  // Validate email format (basic check - you should use a more robust validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 1. Check if the user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log the error
    return false;
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id']; // Get user ID
    $user_email = $result->fetch_assoc()['email']; // Verify email
    $conn->close(); // Close connection after fetching data

    // 2. Generate a unique token/code
    $reset_token = bin2hex(random_bytes(32));  // Securely generate a random token
    // 3.  Create a token table (if it doesn't exist) and insert data
    $reset_token_table_name = "password_reset_tokens"; //  Name of your table

    // Check if the table exists, if not create it
    $table_exists = $conn->query("SHOW TABLES LIKE '$reset_token_table_name'");
    if (!$table_exists->num_rows > 0) {
        $sql_create_table = "CREATE TABLE `$reset_token_table_name` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(32) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if (!$conn->query($sql_create_table)) {
          error_log("Error creating table: " . $conn->error);
          return false;
        }
    }

    $sql_insert_token = "INSERT INTO `$reset_token_table_name` (user_id, token)
                         VALUES ('$user_id', '$reset_token')";

    if (!$conn->query($sql_insert_token)) {
       error_log("Error inserting token: " . $conn->error);
       return false;
    }


    // 4. Send a password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $reset_token;
    $headers = "From: your_email@example.com";

    if (mail($to, $subject, $message, $headers)) {
      // Success!  Delete token after sending.  Important security measure.
      $sql_delete_token = "DELETE FROM `$reset_token_table_name` WHERE user_id = '$user_id' AND token = '$reset_token'";
      if (!$conn->query($sql_delete_token)) {
        error_log("Error deleting token: " . $conn->error);
        //  Don't return false here - continue processing.  It's critical to
        //  keep the account valid, even if deleting the token failed.
      }
      return true;
    } else {
      error_log("Failed to send email"); // Log the failure
      return false;
    }
  } else {
    return false; // User not found
  }
  $conn->close();
}


// Example Usage (for testing -  DO NOT include this in your public facing code)
//  This is only for demonstration and debugging.  In a real application,
//  you'd handle this through a form submission.
/*
$email = "test@example.com"; // Replace with a valid email
if (forgotPassword($email)) {
  echo "Password reset email sent to $email";
} else {
  echo "Failed to reset password for $email";
}
*/
?>
