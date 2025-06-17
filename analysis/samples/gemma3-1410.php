

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with a 'email' field.
// This code is a basic example and should be adapted to your specific setup.

function forgot_password($email, $token, $password, $db) {
  // 1. Validate Email
  $email = trim($email); // Remove leading/trailing whitespace
  if (empty($email)) {
    return "Error: Email cannot be empty.";
  }

  // 2. Check if user exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");
  if ($result->num_rows == 0) {
    return "Error: User not found.";
  }
  $user = $result->fetch_assoc();


  // 3.  Check if token is valid (optional, for security)
  //    This step is crucial for preventing attackers from resetting passwords
  //    if they find an unused token.
  $token_result = $db->query("SELECT id, email, reset_token FROM users WHERE email = '$email' AND reset_token = '$token'");
  if ($token_result->num_rows == 0) {
    return "Error: Invalid token.";
  }
  $token_user = $token_result->fetch_assoc();


  // 4. Update user's password (Secure Hashing is CRITICAL)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $update_query = "UPDATE users SET password = '$hashed_password', reset_token = '', reset_token_expiry = NULL  WHERE email = '$email'";
  $update_result = $db->query($update_query);

  if ($update_result) {
    return "Password reset successful. Please check your email.";
  } else {
    return "Error: Could not reset password.";
  }
}


// Example Usage (Illustrative - Replace with your actual database connection)
// $db = new mysqli("localhost", "username", "password", "database_name");

// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }

// $email = $_POST['email'];
// $token = $_POST['token'];
// $new_password = $_POST['password'];

// $result = forgot_password($email, $token, $new_password, $db);
// echo $result;


?>
