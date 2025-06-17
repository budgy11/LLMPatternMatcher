    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if username is already taken
  $username_query = "SELECT * FROM users WHERE username = '$username'";
  $username_result = mysqli_query($db_connection, $username_query);
  $username_row = mysqli_fetch_assoc($username_result);

  if (!empty($username_row)) {
    $username_error = "Username already exists.";
  }

  // Check if username and email are empty
  if (empty($username) || empty($email)) {
    $username_error = "Username and email are required.";
    $email_error = "Email is required.";
  }

  // If validation passes, insert the user into the database
  if (empty($username_error) && empty($email_error)) {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($db_connection, $sql)) {
      // User registered successfully
      $registration_message = "Registration successful! Please check your email to verify your account.";
    } else {
      // Error during registration
      $registration_message = "Error registering. Please try again.";
      error_log(mysqli_error($db_connection)); // Log the error for debugging
    }
  }
}
?>
