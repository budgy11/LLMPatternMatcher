

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to reset password
function forgotPassword($email, $token, $newPassword, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if Token Exists and is Valid
  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $query = "SELECT token, expires FROM password_resets WHERE user_id = ? AND token = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $user_id, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // 3. Update Password and Expiration
      $query = "UPDATE users SET password = ? WHERE id = ?";
      $stmt = $db->prepare($query);
      $stmt->bind_param("ss", password_hash($newPassword, PASSWORD_DEFAULT), $user_id);
      $stmt->execute();

      // Delete the reset token
      $query = "DELETE FROM password_resets WHERE user_id = ? AND token = ?";
      $stmt = $db->prepare($query);
      $stmt->bind_param("is", $user_id, $token);
      $stmt->execute();
      return "Password reset successfully.  A new password has been set for your account.";

    } else {
      return "Invalid token.  Please request a new password reset link.";
    }
  } else {
    return "User with this email address not found.";
  }

  // Close Statements
  $stmt->close();
  return "Error resetting password. Please try again."; // Generic error message. Better logging is recommended.
}


// Example Usage (Simulating a request)
// Assuming you have a form with email, token, and newPassword

// Example Database connection (Replace with your actual connection)
$db = new mysqli($host, $username, $password, $dbname);

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}


//  Simulating user data from a form
$email = $_POST['email'];
$token = $_POST['token'];
$newPassword = $_POST['newPassword'];

// Call the forgotPassword function
$result = forgotPassword($email, $token, $newPassword, $db);
echo $result;  // Display the result (e.g., success message or error)


// Close the connection
$db->close();
?>
