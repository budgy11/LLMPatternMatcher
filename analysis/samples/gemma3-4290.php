    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate the input data
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Validate password strength (simple example - can be expanded)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if username and password are empty
  if (empty($username) || empty($password)) {
    $username_error = "Username and password cannot be empty.";
  }

  // Hash the password (important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if registration was successful
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    // Insert the user data into the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    echo "Registration successful!  You have been redirected.  <a href='login.php'>Click here to log in</a>";  // Redirect or display a success message
    $stmt->close();
    $conn->close();
  }
}
?>
