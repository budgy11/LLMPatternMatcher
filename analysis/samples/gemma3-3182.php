    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input (Important for security!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (Add more robust validation as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // Hash the password (Never store passwords in plain text!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password);

  if ($stmt->execute()) {
    $success_message = "Registration successful! You have been created successfully.";
  } else {
    $error_message = "Registration failed. " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
  
}
?>
