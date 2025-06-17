    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file
require_once 'db_connect.php'; // Assuming you have a db_connect.php file

/**
 * Registers a new user in the database.
 *
 * @param string $username      The desired username.
 * @param string $password     The user's password.
 * @param string $email        The user's email address.
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $password, $email) {
  // Input validation (Basic - Expand for more robust validation)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Required fields are missing
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Hash the password (Important for security!)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("sss", $username, $hashedPassword, $email);

  // Execute the query
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    // Handle errors -  Important for debugging
    error_log("Error registering user: " . $stmt->error);
    return false;
  }

  // Close the statement
  $stmt->close();
}

// Example Usage:
// $username = "newuser";
// $password = "securepassword";
// $email = "newuser@example.com";

// if (registerUser($username, $password, $email)) {
//   echo "User registered successfully!";
// } else {
//   echo "User registration failed.";
// }


?>
