    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file (adjust the path as needed)
require_once 'config.php'; // Assuming your database connection is in config.php

/**
 * Registers a new user in the database.
 *
 * @param string $username
 * @param string $password
 * @param string $email
 * @return bool|string True on success, or an error message string on failure.
 */
function registerUser(string $username, string $password, string $email) {
  global $conn; // Access the database connection

  // Sanitize and validate inputs (VERY IMPORTANT!)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Check if username or email is empty
  if (empty($username) || empty($email)) {
    return "Username and Email cannot be empty.";
  }

  // Hash the password (important for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if the username or email already exists
  $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    return "Username or Email already exists.";
  }

  // Insert the new user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    // Handle database errors (important for debugging)
    error_log("Database error: " . $stmt->error); // Log the error for debugging
    return "Failed to register user. Database error.";
  }
}


// Example Usage (Demonstration - Don't use this directly in a web form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_result = registerUser($username, $password, $email);

  if ($registration_result === true) {
    echo "<p style='color:green;'>User registered successfully!</p>";
  } else {
    echo "<p style='color:red;'>Error: " . $registration_result . "</p>";
  }
}

?>
