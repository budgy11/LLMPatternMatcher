    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Hash the password (IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if the username is already taken
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db_conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $username_error = "Username already exists.";
  }

  // If all validations pass, insert the user into the database
  if (empty($username_error) && empty($email_error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($db_conn, $sql)) {
      // Registration successful
      $registration_message = "Registration successful! Please check your email to verify your account.";
    } else {
      // Registration failed
      $registration_message = "Error: " . mysqli_error($db_conn);
    }
  }
}
?>
