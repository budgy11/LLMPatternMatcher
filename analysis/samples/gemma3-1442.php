

<?php

// Replace with your database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to reset password
function forgotPassword($email, $new_password, $db) {
  // Validate email format (basic check, consider a more robust solution)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Update the user's password in the database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

  if ($db->query($sql) === TRUE) {
    return "Password reset email sent successfully.";
  } else {
    return "Error resetting password. " . $db->error;
  }
}

// Example Usage (simulated - you'll need to integrate this with your form)
// Assume $email is the user's email address submitted from the form
// Assume $new_password is the user's new password submitted from the form

// Example Database Connection (replace with your actual connection)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Placeholder for form input (replace with your actual form handling)
$email = $_POST['email'];
$new_password = $_POST['new_password'];


// Call the forgotPassword function
$result = forgotPassword($email, $new_password, $conn);

// Display the result
echo $result;
?>
