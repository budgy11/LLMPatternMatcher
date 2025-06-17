        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Replace with your database credentials and table names
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to reset password
function forgot_password($email, $new_password, $db) {
  // Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Update the user's password in the database
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$hashed_password, $user['id']]);

  return true; // Password reset successful
}


// Example Usage (Demonstration - DO NOT USE IN PRODUCTION WITHOUT SECURE HANDLING)
// This is just for demonstration, it's not a complete and secure form!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  // Validate input (important for security - this is basic)
  if (empty($email) || empty($new_password)) {
    echo "Error: Email and password are required.";
  } else {
    // Call the forgot_password function
    $success = forgot_password($email, $new_password, $db);

    if ($success) {
      echo "Password reset email sent successfully!";
    } else {
      echo "Error: Could not reset password.  Please check your email or contact support.";
    }
  }
}

// Database connection (using PDO - recommended)
try {
  $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit;
}

?>
