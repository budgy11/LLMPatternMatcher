    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the Database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 3. Bind Parameters (Important for security - prevents SQL injection)
  $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
  $stmt->bind_param("ss", $username, $password_hash);

  // 4. Execute the Query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 5. Close the Statement and Connection
  $stmt->close();
  $conn->close();

  return true; // Indicate successful registration
}

// --- Example Usage (for demonstration - not a complete web form)
// Assume this is part of a form submission process

// Get user input (from a form, for example)
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Validate input (VERY IMPORTANT - you need to add proper validation here!)
if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required!";
    exit; // Stop further processing
}

// Call the registration function
if (registerUser($username, $password, $email)) {
  echo "Registration successful!  You have been redirected.";
  // Redirect to a login page or success page
} else {
  echo "Registration failed. Please try again.";
}

?>
