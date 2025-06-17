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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate input data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format.";
    }
    // Validate password (example: minimum length)
    if (strlen($password) < 8) {
      $error_message = "Password must be at least 8 characters long.";
    }

    // Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "Registration failed.  Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    }

}
?>
