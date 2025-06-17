

<?php
session_start(); // Start the session to manage user logins

/**
 * Handles user login.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **Important:  This is a VERY basic example for demonstration.  Never use this in a production environment!**
  // **Security Considerations:**  This example is extremely vulnerable to attacks.
  //  - It does not properly hash or salt passwords.
  //  - It's easy to brute-force.
  //  - It doesn't use any input validation.

  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'secure_password'
  ];

  // Basic username check
  if (isset($validUsers[$username])) {
    // Basic password check (VERY INSECURE)
    if ($validUsers[$username] === $password) {
      // Login successful
      $_SESSION['username'] = $username; // Store the username in the session
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // Username not found
    return false;
  }
}

// --- Example Usage (in your PHP script - e.g., index.php) ---

// Check if the user is already logged in
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - redirect to a different page, show a success message, etc.
    echo '<p>Login successful! You are now logged in as ' . htmlspecialchars($username) . '</p>';
    header('Location: user_dashboard.php'); // Redirect to a protected page
    exit(); // Important to stop further script execution
  } else {
    // Login failed
    echo '<p style="color: red;">Invalid username or password.</p>';
  }
}
?>
