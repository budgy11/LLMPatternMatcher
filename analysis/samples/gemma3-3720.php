    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

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

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("sss", $username, $hashed_password, $email); // "s" for string
    $stmt->execute();
    return true; // Registration successful
  } else {
    return false; // Failed to prepare statement
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (from a form submission - you'll need to integrate this with a form)
// This is just a demonstration, you'd normally get the data from a form.

// Example data (replace with actual form data)
$username = "john_doe";
$password = "secure_password123";
$email = "john.doe@example.com";

// Register the user
if (registerUser($username, $password, $email)) {
  echo "Registration successful! You can now log in.";
} else {
  echo "Registration failed. Please try again.";
}

?>
