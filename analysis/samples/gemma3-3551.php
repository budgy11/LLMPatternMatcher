    <br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate input
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Validate password length (adjust as needed)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if username is empty
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  }

  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if all validations pass
  if (empty($username_error) && empty($password_error) && empty($email_error)) {
    // SQL query to insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Use prepared statement for security

    if ($stmt->execute([$username, $email, $hashed_password])) {
      // Registration successful
      $success_message = "Registration successful!  You have been logged in.";
    } else {
      // Registration failed
      $error_message = "Error registering user. Please try again.";
    }

    // Close the statement
    $stmt->close();
  }
}
?>
