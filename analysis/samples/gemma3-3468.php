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

// Configuration (replace with your database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate data (IMPORTANT - add more validation as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9]+$/", $username) || // Username should only contain alphanumeric characters
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $errors = "Invalid email or username.  Username must contain only alphanumeric characters.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }
    // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password);  // "ss" means two strings

  // Execute the query
  if ($stmt->execute()) {
    $success = "Registration successful!";
  } else {
    $error = "Registration failed.  Error: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
