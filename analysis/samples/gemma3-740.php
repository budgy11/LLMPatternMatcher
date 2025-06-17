        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @param array $users An associative array of users and their passwords.
 *                      Example: ['user1' => 'password1', 'user2' => 'password2']
 *
 * @return int|false The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password, array $users) {
  // Sanitize input (basic - consider more robust sanitization in production)
  $username = trim($username);
  $password = trim($password);

  // Check if the user exists
  if (isset($users[$username])) {
    // Verify the password
    if (password_verify($password, $users[$username])) {
      // User exists and password matches - return the user ID
      return $username; // Or, you could return the user's ID from a database
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// Example Usage:

// Sample user data (in a real application, this would come from a database)
$users = [
  'user1' => '$2y$11$e9i6a8o7s9n5k3m2j1l8o3p4q2r1t5u3v4x9z2a8x8x7y6z5p4w3a2b1c', // Password Hashed
  'user2' => '$2y$11$d9i6a8o7s9n5k3m2j1l8o3p4q2r1t5u3v4x9z2a8x8x7y6z5p4w3a2b1c',
];

// Get username and password from the user (e.g., from a form)
$username = $_POST['username'];
$password = $_POST['password'];

// Perform the login
$userId = loginUser($username, $password, $users);

// Handle the result
if ($userId) {
  // Login successful
  echo "Welcome, " . $userId . "!";
  // You can now redirect the user to a different page, etc.
} else {
  // Login failed
  echo "Invalid username or password.";
}

?>
