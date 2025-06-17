    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Form handling (CSRF protection - important!)
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input to prevent common vulnerabilities
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Password strength check (example - customize as needed)
  $password_length = strlen($password);
  if ($password_length < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if username is already taken
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($GLOBALS['db_host'], $sql);
  if (mysqli_num_rows($result) > 0) {
    $username_error = "Username already exists.";
  }

  // If validation passes, insert the user into the database
  if (empty($username_error) && empty($password_error) && empty($email_error)) {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to prevent SQL injection
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if (mysqli_query($GLOBALS['db_host'], $sql)) {
      // Registration successful
      $registration_message = "Registration successful! Please check your email to verify your account.";
    } else {
      // Error during registration
      $registration_message = "Error: " . mysqli_error($GLOBALS['db_host']);
    }
  }
}
?>
