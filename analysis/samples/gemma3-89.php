

<?php

/**
 * User Login Function
 *
 * This function handles user login.  It should be called with the username
 * and password provided by the user.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to authenticate.
 * @return int|string A user ID if login is successful, or an error message if not.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  In a real application, you should NOT store passwords in plain text!**
  // This is just a simplified example for demonstration.
  // Use a secure hashing algorithm like bcrypt or Argon2.

  // **Example Data - Replace with your database connection and data retrieval logic.**
  $validUsers = [
    'john.doe@example.com' => 'password123',
    'jane.smith@example.com' => 'securePassword',
  ];

  // Normalize username for case-insensitive comparison
  $username = strtolower($username);

  if (isset($validUsers[$username])) {
    // **SECURITY WARNING:**  Never store passwords in plain text.
    // This is a placeholder for your actual password verification logic.
    if ($validUsers[$username] === $password) {
      // User login successful
      // Generate a session ID (for security)
      session_start();  // Start a session
      $sessionId = session_id();

      // Store user data in the session (for example)
      $_SESSION['userId'] = $username; // or perhaps a unique ID from your database

      return $username; // Return the username (or user ID)
    } else {
      return 'Password mismatch.';
    }
  } else {
    return 'Invalid username.';
  }
}

// **Example Usage (for testing):**

// Simulate user input
$username = 'john.doe@example.com'; // Replace with actual input
$password = 'password123'; // Replace with actual input

// Perform the login
$result = loginUser($username, $password);

if ($result === 'Invalid username.') {
  echo "<p>Error: Invalid username.</p>";
} elseif ($result === 'Password mismatch.') {
  echo "<p>Error: Password mismatch.</p>";
} elseif ($result === 'Invalid username.' || $result === 'Password mismatch.') {
  echo "<p>Error: " . $result . "</p>";
} else {
  echo "<p>Login successful.  User: " . $result . "</p>";
}


// Example for checking if the user is logged in:
session_start();
if (isset($_SESSION['userId'])) {
  echo "<p>You are logged in as: " . $_SESSION['userId'] . "</p>";
} else {
  echo "<p>You are not logged in.</p>";
}

?>
