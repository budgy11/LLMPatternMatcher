    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values!
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_database_user";
$dbPass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailError = "Invalid email address.";
  }

  // Password validation (basic - you should use a stronger hashing method in production)
  if (empty($password)) {
    $passwordError = "Password cannot be empty.";
  } else if (strlen($password) < 8) {
    $passwordError = "Password must be at least 8 characters long.";
  }

  // Check if username is already taken
  $usernameCheck = "SELECT * FROM users WHERE username = '" . $username . "'";
  $usernameResult = mysqli_query($GLOBALS["dbHost"], $usernameCheck);
  if (mysqli_num_rows($usernameResult) > 0) {
    $usernameError = "Username already taken.";
  }

  // Hash the password (Important for security!)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // If all validations pass, insert the new user into the database
  if (empty($usernameError) && empty($emailError) && empty($passwordError)) {
    $sql = "INSERT INTO users (username, email, password) VALUES ('" . $username . "', '" . $email . "', '" . $hashedPassword . "')";

    if (mysqli_query($GLOBALS["dbHost"], $sql)) {
      $registrationSuccess = true;
      $successMessage = "Registration successful!  You have been logged in.";
    } else {
      $registrationError = "Error: " . mysqli_error($GLOBALS["dbHost"]);
    }
  }
}

// Start the HTML form
?>
