        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <button type="submit">Reset Password</button>
    </form>

    <p>Don't have an account? <a href="register.php">Sign Up</a></p>  <!-- Replace with your registration page -->
</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

//  Reset Password Function
function reset_password($email, $new_password) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize email for security
  if (empty($email)) {
    return false; // Invalid email
  }

  // 2. Database Connection
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage()); // Log error for debugging
    return false;
  }


  // 3.  Check if User Exists
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($user)) {
    // User not found
    return false;
  }

  // 4.  Hash the New Password (Important for Security!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 5. Update the Password
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
  $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
  $stmt->execute();

  if ($stmt->rowCount() === 0) {
    // Update failed
    return false;
  }

  return true; // Password reset successful
}



// Example Usage (Demonstration - Don't use directly in production without validation and sanitization)
//  This is for demonstration purposes only - NEVER expose this directly to the user.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['new_password'])) {
  $email = $_POST['email'];
  $new_password = $_POST['new_password'];

  if (reset_password($email, $new_password)) {
    echo "<p style='color: green;'>Password reset successfully! Check your email.</p>";
  } else {
    echo "<p style='color: red;'>Password reset failed. Please try again.</p>";
  }
}

?>
