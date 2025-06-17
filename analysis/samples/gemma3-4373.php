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

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validate the data
  $errors = [];

  // Check if username is empty
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }

  // Check if username is too short (example)
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Check if email is empty
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }

  // Check if email is valid (basic validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check if passwords match
  if ($password != $confirm_password) {
    $errors[] = "Passwords must match.";
  }

  // Check if password is too short (example)
  if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Escape the data to prevent SQL injection
    $username = mysqli_real_escape_string($connection, $username);
    $email = mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);

    // Hash the password (important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($connection, $sql)) {
      // Registration successful
      echo "Registration successful! Please check your email for verification instructions.";
    } else {
      // Registration failed
      echo "Error registering: " . mysqli_error($connection);
    }
  }
}

// Display the registration form
?>
