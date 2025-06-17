    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input (add more validation as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize input (IMPORTANT: Always sanitize user input to prevent SQL injection and XSS)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Check if username is empty
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  }

  // Check if password is empty
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  }

  // Check if username already exists (optional but recommended)
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db_host, $sql);
  if (mysqli_num_rows($result) > 0) {
    $username_error = "Username already exists.";
  }

  // Hash the password (VERY IMPORTANT - Never store passwords in plain text!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the user into the database
  if (empty($username_error) && empty($password_error) && empty($email_error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($db_host, $sql)) {
      $registration_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $registration_message = "Error creating user.  " . mysqli_error($db_host);
    }
  }
}
?>
