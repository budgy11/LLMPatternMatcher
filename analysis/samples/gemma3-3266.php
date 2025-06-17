    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_config.php'; // Replace with your database configuration file name

/**
 * Registers a new user in the database.
 *
 * @param string $username  The desired username.
 * @param string $password The desired password.
 * @param string $email    The user's email address.
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $password, $email) {
  // Input Validation (Crucial for security!)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Required fields must be filled
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    return true;
  } else {
    // Handle the error - Log the error for debugging
    error_log("Error registering user: " . $stmt->error);
    return false;
  }

  // Close the statement
  $stmt->close();
}


// Example Usage (for testing - remove in a real application)
//
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $username = $_POST["username"];
//   $password = $_POST["password"];
//   $email = $_POST["email"];
//
//   if (registerUser($username, $password, $email)) {
//     echo "User registered successfully!";
//   } else {
//     echo "Failed to register user. Please try again.";
//   }
// }
//
?>
