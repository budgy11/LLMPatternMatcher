    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login authentication and returns a session cookie
 * if successful.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Simulate a database lookup - Replace this with your actual database query
  $users = [
    ['username' => 'john.doe', 'password' => 'secret123'],
    ['username' => 'jane.smith', 'password' => 'securepass'],
  ];

  // Convert input to lowercase for case-insensitive comparison
  $username = strtolower($username);
  $password = strtolower($password);

  foreach ($users as $user) {
    if ($user['username'] === $username && $user['password'] === $password) {
      // Login successful
      // Set a session cookie to track the user
      session_start();
      $_SESSION['username'] = $username;  // Store the username in the session
      return true;
    }
  }

  // Login failed
  return false;
}

// Example Usage (demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security - implement robust validation)
  if (empty($username) || empty($password)) {
    echo "<p style='color:red;'>Username and password cannot be empty.</p>";
  } else {
    // Call the login function
    if (loginUser($username, $password)) {
      // Redirect to a secure page after successful login
      header("Location: secure_page.php"); // Redirect to a protected page
      exit();
    } else {
      echo "<p style='color:red;'>Invalid username or password.</p>";
    }
  }
}
?>
