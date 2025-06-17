
<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

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
  // Sanitize input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input data
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (empty($error)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $email, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      $success = "Registration successful! Please log in.";
    } else {
      $error = "Registration failed. " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
