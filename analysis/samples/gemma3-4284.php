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

// Database configuration (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (Essential for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9_]+$/", $username) // Basic username validation
  ) {
    $error = "Username must contain only letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error = "Invalid email format.";
  } elseif (
    strlen($password) < 8 // Minimum password length
  ) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

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
}
?>
