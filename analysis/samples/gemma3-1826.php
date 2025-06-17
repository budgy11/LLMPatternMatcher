

<?php

// This is a simplified example for demonstration purposes.
// In a production environment, you should:
// 1.  Securely store passwords (e.g., hash them with bcrypt or Argon2).
// 2.  Implement rate limiting to prevent brute-force attacks.
// 3.  Use email libraries to send actual emails instead of just printing to the screen.
// 4.  Handle errors gracefully and securely.


// Configuration (replace with your database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to reset password
function forgot_password($email, $new_password, $db) {
  // 1. Validate Email (Basic)
  if (empty($email)) {
    return "Invalid email address.";
  }

  // 2.  Check if user exists (Simple check - improve with more robust validation)
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");
  if ($result->num_rows == 0) {
    return "User not found.";
  }

  $user_data = $result->fetch_assoc();
  $user_id = $user_data['id'];
  $user_email = $user_data['email'];

  // 3. Hash the New Password (Crucial for Security!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 4. Update the Password in the Database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'";
  if (!$db->query($sql)) {
    return "Error updating password. " . $db->error;
  }

  return "Password reset email sent. Check your inbox.";
}


// Example Usage (Simulate a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  // Database Connection (replace with your connection logic)
  $db = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $result = forgot_password($email, $new_password, $db);
  echo $result; // Output the result to the user
} else {
  // Display the form (for demonstration)
  ?>
