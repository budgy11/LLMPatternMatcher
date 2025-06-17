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

// Database configuration (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // 3. Prepare the statement (important for security)
  $stmt = $conn->prepare($sql);

  // 4. Bind the parameters
  $stmt->bind_param("ss", $username, $email);

  // 5. Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 6. Execute the statement
  if ($stmt->execute($hashed_password)) {
    // Success!
    return true;
  } else {
    // Error executing query
    return false;
  }

  // 7. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage:  (This would likely come from a form submission)
// For demonstration purposes, we'll simulate form input
$username = "john.doe";
$email = "john.doe@example.com";
$password = "SecurePassword123";

if (registerUser($username, $email, $password)) {
  echo "Registration successful!  You have been logged in.";
} else {
  echo "Registration failed. Please try again.";
}

?>
