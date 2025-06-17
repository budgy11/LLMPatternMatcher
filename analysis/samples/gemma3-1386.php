

<?php

// Configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgot_password($email, $token, $new_password, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 2. Check if token exists
  $query = "SELECT id FROM password_resets WHERE email = '$email' AND token = '$token'";
  $result = $db->query($query);

  if ($result->num_rows > 0) {
    // Token is valid, proceed with password update
    $query = "UPDATE users SET password = '$new_password'";
    $result = $db->query($query);

    if ($result) {
      // 1. Delete the token record (Important - prevent reuse)
      $query = "DELETE FROM password_resets WHERE email = '$email' AND token = '$token'";
      $result = $db->query($query);

      if ($result) {
        return true;
      } else {
        //  Error deleting token - log this!
        error_log("Error deleting password reset token: " . $db->error);
        return false;
      }
    } else {
      // Error updating password - log this!
      error_log("Error updating password: " . $db->error);
      return false;
    }
  } else {
    // Token is invalid - no user with this token
    return false;
  }
}


// Example Usage (Demonstration - Replace with actual form handling)
//  This simulates getting the email, token, and new password from a form.

// Assume you have a form that submits these values.  In a real application,
// you would sanitize and validate these inputs thoroughly before using them.

// Simulate form input
$email = $_POST['email'];
$token = $_POST['token'];
$new_password = $_POST['new_password'];

// *** IMPORTANT SECURITY NOTE: ***
// In a real application, you should NEVER directly use user-provided input
// in database queries.  This is a simplified example for demonstration purposes only.
// You MUST use prepared statements (parameterized queries) to prevent SQL injection vulnerabilities.
// The following is just for demonstration.

// Setup database connection (Replace with your connection details)
$db = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}


// Call the forgot_password function
$reset_success = forgot_password($email, $token, $new_password, $db);

if ($reset_success) {
  echo "Password reset successful!  Please check your email.";
} else {
  echo "Invalid token or email.  Please try again.";
}

$db->close(); // Close the database connection
?>
