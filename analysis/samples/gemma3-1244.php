    Username: <input type="text" name="username" placeholder="Enter your email">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>
  <br><br>
  <a href="forgot_password.php">Forgot Password?</a>  <!-- You'll need to implement forgot password functionality -->

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Function to reset password
function forgotPassword($email, $dbname, $host, $username, $password) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Check if user exists
  $conn = new mysqli($host, $username, $password, $dbname);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userName = $user['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random token
    
    // 4.  Store token in database for user
    $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$userId'";
    if (!$conn->query($sql)) {
      return "Error updating token: " . $conn->error;
    }

    // 5.  Send email with reset link
    $subject = "Password Reset Link";
    $to = $email;
    $headers = "From: your_email@example.com"; // Replace with your email
    $resetLink = "http://localhost/reset_password.php?token=$token"; // Replace with your reset link URL
    $message = "Click on the link above to reset your password.";

    mail($to, $message, $headers);
    return "Password reset email has been sent to $email.";

  } else {
    return "User not found.";
  }

  $conn->close();
}


// Example usage (Handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgotPassword($email, $dbname, $host, $username, $password);
  echo "<p>" . $result . "</p>";
}
?>
