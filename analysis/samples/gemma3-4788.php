    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Check if username or email already exists (optional, but recommended)
  if (usernameExists($username, $db_user, $db_password, $db_name)) {
    $username_error = "Username already exists.";
  }

  if (emailExists($email, $db_user, $db_password, $db_name)) {
    $email_error = "Email address already exists.";
  }

  // Password strength validation (basic example)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql); // Use prepared statement
    $stmt->bind_param("sss", $username, $email, $hashed_password);  // Bind parameters
    $stmt->execute();

    // Redirect to a success page
    header("Location: success.php"); // Replace success.php with your desired redirect
    exit();
  }
}
?>
