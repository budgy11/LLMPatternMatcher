    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Input validation
function validateRegistrationData($username, $email, $password) {
  // Check if inputs are empty
  if (empty($username) || empty($email) || empty($password)) {
    return false;
  }

  // Username validation -  Simple check for length and allowed characters
  if (strlen($username) < 3 || strlen($username) > 20) {
    return false;
  }
  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    return false;  // Only allow alphanumeric and underscore
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // Password validation - Minimum length
  if (strlen($password) < 8) {
    return false;
  }

  return true;
}


// Function to register a new user
function registerUser($username, $email, $password) {
  global $db_host, $db_name, $db_user, $db_password;

  // Validate the data
  if (!validateRegistrationData($username, $email, $password)) {
    return "Registration failed: Invalid data. Please check your input.";
  }

  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    return "Registration failed: Connection error - " . $conn->connect_error;
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password);

  if ($stmt->prepare() === false) {
    return "Registration failed: Error preparing statement.";
  }


  // Execute the query
  if ($stmt->execute() === false) {
    return "Registration failed: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();

  return "Registration successful.  Welcome, " . $username . "!";
}


// Example usage (Handle form submission)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registration_result = registerUser($username, $email, $password);

  echo "<p>" . $registration_result . "</p>";
}
?>
