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

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation (basic - customize as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9]*$/", $username) || // Only alphanumeric characters
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $errors = "Invalid email or username format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }
  
  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful!  You have been redirected.";
    } else {
      $errors = "Registration failed.  " . print_r($stmt->error, true);
    }

    // Close the statement
    $stmt->close();
  }
}

// Database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
