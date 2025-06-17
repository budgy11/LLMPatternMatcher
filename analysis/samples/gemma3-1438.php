
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>
  <h1>Forgot Password</h1>
  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>
</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Function to reset password
function forgotPassword($email, $newPassword, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email format.';
  }

  // 2. Check if user exists
  $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return 'User not found.';
  }

  // 3. Hash the new password (IMPORTANT for security)
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the password in the database
  try {
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedNewPassword, $user['id']]);
    return 'Password reset email sent. Please check your inbox.';
  } catch (PDOException $e) {
    return 'Error updating password: ' . $e->getMessage();
  }
}


// --- Example Usage (This is for demonstration - don't include this in your actual code) ---

// 1. Database connection (using PDO - recommended)
try {
  $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For error handling
} catch (PDOException $e) {
  echo "Database connection error: " . $e->getMessage() . "<br>";
  die(); // Stop script execution if unable to connect
}

// 2. Get email and new password from form (or wherever you get them)
$email = $_POST['email'];
$newPassword = $_POST['new_password'];


// 3. Call the forgotPassword function
$result = forgotPassword($email, $newPassword, $db);

// 4. Display the result
echo $result;


?>
