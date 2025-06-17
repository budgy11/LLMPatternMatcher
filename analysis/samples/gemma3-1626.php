

<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to reset password
function forgotPassword($email, $new_password, $db) {
  // Check if database connection is established
  if (!$db) {
    return false; // Indicate failure
  }

  // Prepare the SQL query
  $sql = "UPDATE users SET password = ? WHERE email = ?";

  // Prepare the statement
  $stmt = $db->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $new_password, $email);

  // Execute the query
  $result = $stmt->execute();

  // Check for errors
  if ($result) {
    return true; // Indicate success
  } else {
    // Handle the error
    error_log("Error resetting password for email: " . $email);
    return false;
  }

  // Close the statement
  $stmt->close();
}

// Example usage (Demonstration - NOT Production Ready)
// Assuming you have a login form that takes email and sets a temporary password
// and you are calling this function here, using the email and new_password
// for the user.

//  For a production system, this would be handled on a form submission
//  and you would likely also send an email with a link to reset the password
//  using a token and a timed expiration.

// Example input (for demonstration)
$email = "test@example.com";  // Replace with the actual email to reset
$new_password = "newSecretPassword";  // Replace with the desired new password


// Call the function
$success = forgotPassword($email, $new_password, $db);

if ($success) {
  echo "Password reset successfully! Please log in with your new password.";
} else {
  echo "Password reset failed. Please try again.";
}

?>
