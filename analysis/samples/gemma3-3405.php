    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Configuration (adjust these as needed)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Validate the input
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows == 0) {
      $error_message = "Registration failed.  Check your database connection.";
    } else {
      $error_message = "Registration successful! Please check your email for verification instructions.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}

?>
