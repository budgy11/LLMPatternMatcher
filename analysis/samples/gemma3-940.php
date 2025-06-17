    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login with username and password verification.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|string Returns the user ID if login is successful, 
 *                   or an error message string if login fails.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  Replace this with your actual database connection and user data.**
  // This is a simplified example and won't work without a database.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass42',
  ];

  // Sanitize input (important for security)
  $username = strtolower($username); // Convert to lowercase for consistency

  if (isset($validUsers[$username])) {
    // Verify password
    if ($validUsers[$username] === $password) {
      // Login successful
      // Generate a session ID (for security - you'd normally use a more robust method)
      $sessionId = bin2hex(random_bytes(32)); // Generate a secure session ID

      // Store session ID in session variable (for simplicity, but proper session management is crucial)
      session_start();
      $_SESSION['userId'] = $username; // Store the username, not the password!
      $_SESSION['sessionId'] = $sessionId; 

      return $username; // Return the username (or user ID if you have one)
    } else {
      return "Incorrect password.";
    }
  } else {
    return "User not found.";
  }
}


// **Example Usage:**

// Get username and password from form submission (example)
// $username = $_POST['username'];
// $password = $_POST['password'];

// if (isset($username) && isset($password)) {
//   $loginResult = loginUser($username, $password);
//   if ($loginResult === 'Incorrect password.') {
//     echo "<p style='color:red;'>Incorrect password.</p>";
//   } elseif ($loginResult === 'User not found.') {
//     echo "<p style='color:red;'>User not found.</p>";
//   } else {
//     echo "<p>Login successful!  User ID: " . $loginResult . "</p>";
//     // Redirect to a secure page after login
//   }
// } else {
//   echo "<p>Please fill in the form.</p>";
// }


?>
