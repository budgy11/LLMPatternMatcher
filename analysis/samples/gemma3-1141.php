    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * Login function to authenticate users.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, or false if failed.
 */
function login(string $username, string $password) {
  // **IMPORTANT: NEVER STORE PASSWORDS IN PLAIN TEXT!**
  // This example demonstrates the *concept* of authentication.
  // In a real application, you should hash passwords using a secure algorithm
  // like bcrypt or Argon2.

  // **Simulated User Database (Replace with your actual database connection)**
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securepass',
    'admin' => 'adminpassword'
  ];

  // Check if the username exists in the database
  if (isset($users[$username])) {
    // Compare the provided password with the stored hashed password
    if (password_verify($password, $users[$username])) {  // Use password_verify for secure comparison
      // Login successful
      // Generate a session token (for security)
      $session_token = bin2hex(random_bytes(32));

      // Store the session token in a session variable.
      // **This is a simplified example. In a production environment,
      // use a more robust session management system like PHP's built-in
      // session functions or a dedicated session library.**
      $_SESSION['user_id'] = $username; // Store the username as the user_id.  Better to store a unique ID.
      $_SESSION['session_token'] = $session_token;


      return $username; // Return the username (or user ID)
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}


// **Example Usage (For testing)**
//  1.  Get username and password from form submission (example)

//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];
//    $loginResult = login($username, $password);

//    if ($loginResult) {
//      echo "Login successful! User ID: " . $loginResult;
//    } else {
//      echo "Login failed.  Invalid username or password.";
//    }
//  }

//  2.  A simple example for demonstration:
//  $user_id = login('john.doe', 'password123');
//  if ($user_id) {
//      echo "User login successful. User ID: " . $user_id;
//  } else {
//      echo "Login failed.";
//  }

?>
