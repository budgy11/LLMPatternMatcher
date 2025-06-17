
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Crucial for security)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic input validation
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Username validation
    !preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) || // Email validation
    $password_length < 6) { // Password length validation (minimum 6 characters)
    $errors = "Invalid input. Please check your username, email, and password.";
  } else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $email, $hashed_password);

      // Execute the query
      if ($stmt->execute()) {
        // Success
        echo "Registration successful! Please verify your email.";
      } else {
        // Error executing query
        echo "Error: " . $stmt->error;
      }

      // Close the statement
      $stmt->close();
    } else {
      // Error preparing statement
      echo "Error preparing statement.";
    }
  }
}
?>
