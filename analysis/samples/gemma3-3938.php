    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

/**
 * Registers a new user.
 *
 * @param string $username
 * @param string $password
 * @param string $email
 * @param string $firstname
 * @param string $lastname
 *
 * @return bool True on successful registration, false otherwise.
 */
function registerUser(string $username, string $password, string $email, string $firstname, string $lastname) {
  // Validate input (Crucially important for security!)
  if (empty($username) || empty($password) || empty($email) || empty($firstname) || empty($lastname)) {
    return false; // Required fields are empty
  }

  // Sanitize input to prevent SQL injection (Very important!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $firstname = filter_var($firstname, FILTER_SANITIZE_STRING);
  $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);


  // Check if username already exists
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $stmt->close();
    return false; // Username already exists
  }

  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("sssss", $username, $hashed_password, $email, $firstname, $lastname);

    if ($stmt->execute()) {
      $stmt->close();
      return true; // Registration successful
    } else {
      $error_message = $stmt->error;
      $stmt->close();
      // Handle errors - log or display a user-friendly message
      error_log("Registration error: " . $error_message);
      return false;
    }
  } else {
    // Handle preparation error - log or display a user-friendly message
    error_log("Failed to prepare registration statement");
    return false;
  }
}

// --- Example Usage ---
// If you are in a web page and want to use this function:

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];

  if (registerUser($username, $password, $email, $firstname, $lastname)) {
    echo "Registration successful! Please check your email for verification instructions.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
