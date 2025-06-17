        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// For demonstration, we'll use a dummy database.  Replace this with your actual database connection.
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

/**
 * Registers a new user.
 *
 * @param string $username The desired username.
 * @param string $password The desired password.
 * @param string $email The user's email address.
 *
 * @return bool True if registration was successful, false otherwise.
 */
function registerUser($username, $password, $email) {
  global $db; // Access the database connection

  // Input validation - VERY IMPORTANT!  Protect against SQL injection and invalid data.
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Required fields are missing
  }

  // Sanitize input to prevent SQL injection
  $username = $db->real_escape_string($username);
  $password = $db->real_escape_string($password);
  $email = $db->real_escape_string($email);


  // Check if the username or email already exists
  $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    return false; // Username or email already exists
  }

  // Hash the password - NEVER store passwords in plain text!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if ($db->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    // Handle database error
    error_log("Error registering user: " . $db->error); // Log the error for debugging
    return false;
  }
}

// --- Example Usage (For testing) ---

// Simulate a registration request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registered successfully!";
  } else {
    echo "User registration failed.  Please try again.";
  }
}
?>
