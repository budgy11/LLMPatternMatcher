    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter your username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Configuration (adjust these as needed)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Input Validation and Sanitization (VERY IMPORTANT!)
  $username = trim($username); // Remove whitespace
  $password = trim($password);
  $email = trim($email);

  // Basic validation (you should add more robust validation)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Indicate failure
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Sanitize input (more robust sanitization is highly recommended)
  $username = $conn->real_escape_string($username);
  $email = $conn->real_escape_string($email);


  // 3. Check if the username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return false; // Username already exists
  }

  // 4. Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 5. Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 6. Close the connection
  $conn->close();
}

// Example Usage (for demonstration)
// This is just an example; you would typically handle this through a form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registered successfully!";
  } else {
    echo "Failed to register user. Please try again.";
  }
}
?>
