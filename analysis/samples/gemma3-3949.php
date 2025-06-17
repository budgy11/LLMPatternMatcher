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

// Database Configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Validate password strength (Basic example - expand for better security)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if all validation passed
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (empty($error_message)) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("ss", $username, $email, $hashed_password);

      // Execute the statement
      if ($stmt->execute()) {
        // Success!  Redirect to a welcome page or login page
        header("Location: welcome.php");  // Replace with your welcome page
        exit();
      } else {
        $error_message = "Query failed: " . $stmt->error;
      }
    } else {
      $error_message = "Prepare statement failed.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
