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

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and prepare the SQL query
  $username = $conn->real_escape_string($username);
  $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
  $email = $conn->real_escape_string($email);

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  // 3. Execute the query
  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the connection
  $conn->close();
}

// Example usage (This would typically be in a form submission handler)
// Assuming the form data is in $username, $password, and $email variables
//  (In a real application, you'd use $_POST, $_GET, etc.)

// Example data (Replace with actual form data)
$username = "testuser";
$password = "SecurePassword123!";
$email = "testuser@example.com";

// Register the user
if (registerUser($username, $password, $email)) {
  echo "User registration successful!";
} else {
  echo "User registration failed.  Please try again.";
}

?>
