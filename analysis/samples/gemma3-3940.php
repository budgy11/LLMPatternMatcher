  <label for="username">Username:</label>
  <input type="text" id="username" name="username" required><br><br>

  <label for="password">Password:</label>
  <input type="password" id="password" name="password" required><br><br>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required><br><br>

  <label for="firstname">First Name:</label>
  <input type="text" id="firstname" name="firstname" required><br><br>

  <label for="lastname">Last Name:</label>
  <input type="text" id="lastname" name="lastname" required><br><br>

  <input type="submit" value="Register">
</form>


<?php

// Database credentials - Replace with your actual database details
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);


  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  }

  // Password validation (minimum length and alphanumeric)
  if (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
    $error_message = "Password must contain only letters and numbers.";
  }

  // Check if username is already registered
  $username_check = mysqli_query($GLOBALS['db_host'], "SELECT id FROM users WHERE username = '$username'");
  $username_result = mysqli_fetch_assoc($username_result);

  if ($username_result && $username_result["id"] > 0) {
    $error_message = "Username already exists. Please choose another.";
  }

  // If no errors, proceed with registration
  if (empty($error_message)) {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS['db_host'], $sql)) {
      // Registration successful
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      // Registration failed
      $error_message = "An error occurred while registering. Please try again.";
    }
  }
}
?>
