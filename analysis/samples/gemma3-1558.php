    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 3.  Create a temporary password reset record in the database
  $query = "INSERT INTO password_resets (email, token, created_at) VALUES ('$email', '$token', NOW())";

  $mysqli = new mysqli($host, $username, $password, $database);

  if ($mysqli->connect_error) {
    return "Database connection failed: " . $mysqli->connect_error;
  }

  if ($mysqli->query($query) === FALSE) {
    return "Failed to insert password reset record: " . $mysqli->error;
  }

  $mysqli->close();

  // 4. Send an email with a reset link
  $subject = "Password Reset";
  $message = "Click this link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a>";
  $headers = "From: your_email@example.com" . "\r
"; // Replace with your email address

  if (mail($email, $subject, $message, $headers)) {
    return "Password reset link sent to your email address.";
  } else {
    // Handle email sending failure - important for debugging
    return "Failed to send password reset email.";
  }
}

// Example usage (from a form submission or other trigger)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgot_password($email);
  echo $result;
}
?>
