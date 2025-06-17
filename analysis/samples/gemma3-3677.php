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

// Database Configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// --- Registration Function ---
function registerUser($username, $password, $email) {
  // 1. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize Input (Important for security)
  $username = $conn->real_escape_string($username); // Escapes for SQL
  $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
  $email = $conn->real_escape_string($email);

  // 3.  Prepare and Execute the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the connection
  $conn->close();
}

// --- Example Usage (For demonstration -  You'd typically handle this in a form) ---
// Form data (Simulating user input)
$newUsername = "testuser";
$newPassword = "SecretPassword123";
$newEmail = "testuser@example.com";

// Call the registration function
if (registerUser($newUsername, $newPassword, $newEmail)) {
  echo "User registration successful!  Username: $newUsername";
} else {
  echo "User registration failed. Please try again.";
}

?>
