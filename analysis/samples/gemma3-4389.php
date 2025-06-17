    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize inputs (VERY IMPORTANT!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize inputs to prevent SQL injection and XSS attacks
  $username = mysqli_real_escape_string($connection, $username);
  $email = mysqli_real_escape_string($connection, $email);
  $password = mysqli_real_escape_string($connection, $password);

  // Validate email format (basic check, use a more robust validation if needed)
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


  // Hash the password using password_hash() - IMPORTANT for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Check if username and email already exist (optional, but recommended)
  $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($connection, $sql);

  if (mysqli_num_rows($result) > 0) {
    $error = "Username or email already exists.";
  }


  // If validation passes, insert the user into the database
  if (empty($username_error) && empty($password_error) && empty($error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($connection, $sql)) {
      $success = "Registration successful!  You have been logged in.";
    } else {
      $error = "Error inserting data into database. " . mysqli_error($connection);
    }
  }
}
?>
