    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login authentication.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false Returns the user ID on successful login, or false on failure.
 */
function loginUser(string $username, string $password) {
  // In a real application, you would connect to a database here.
  // This example uses a hardcoded user database for demonstration purposes only.

  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass',
    'admin' => 'admin123'
  ];

  // Check if the username exists in the user database
  if (isset($validUsers[$username])) {
    // Check if the password matches
    if (hash('sha256', $password) === hash('sha256', $validUsers[$username])) {
      // Authentication successful
      session_start();  // Start a session to store user information
      $_SESSION['userId'] = $username; // Store the username (could also store the user ID)
      return $username; // Return the username as the user ID
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}


/**
 * Example Usage
 */

// Get username and password from form input (example)
$username = $_POST['username'];
$password = $_POST['password'];


// Attempt to login the user
$userId = loginUser($username, $password);

if ($userId) {
  // Login successful
  echo "Login successful! User ID: " . $userId . "<br>";
  // You can redirect the user to a different page here.
  // header("Location: user_dashboard.php");  // Example redirect
  exit(); // Stop further execution of the script
} else {
  // Login failed
  echo "Login failed.  Invalid username or password.<br>";
}

?>
