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

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_db_username";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize inputs to prevent security vulnerabilities
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate inputs (Basic validation - expand as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  }

  // Check if the username already exists (optional - depends on your needs)
  // This example assumes you want to prevent duplicate usernames
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT COUNT(*) FROM users WHERE username = '$username'";
  $result = $conn->query($sql);
  if ($result) {
    $count = $result->fetch_row()[0];
    if ($count > 0) {
      $error = "Username already exists. Please choose another.";
    }
  }

  // If no errors, proceed with registration
  if (empty($error)) {
    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($sql)) {
      $success = "Registration successful!  Please check your email to verify.";
    } else {
      $error = "Error registering user: " . $conn->error;
    }
  }

  // Close the database connection
  $conn->close();
}

?>
