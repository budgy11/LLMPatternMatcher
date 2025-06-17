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

// Include the database connection file (assuming you have one named 'db_config.php')
require_once 'db_config.php';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form input
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Check if the username is empty
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Please enter a valid email address.";
  }

  // Check if the password is empty
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Hash the password (VERY IMPORTANT FOR SECURITY!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if all validations passed
  if (empty($username_error) && empty($email_error) && empty($password_error)) {

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $email, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      $registration_message = "Registration successful! Please log in.";
    } else {
      // Registration failed
      $registration_message = "Registration failed.  " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}
?>
