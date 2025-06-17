
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
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
 * This function handles user login using a simple username/password scheme.
 * **Important:**  This is a basic example for demonstration purposes.  
 *  For production environments, you should use a secure authentication method 
 *  like hashing passwords and using sessions or tokens.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT:  In a real application, you should NEVER store passwords in plain text.**
  // This example uses a hardcoded database for simplicity.
  // You should hash passwords using a strong hashing algorithm like bcrypt.

  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass456',
  ];

  // Check if the username exists
  if (isset($validUsers[$username])) {
    // Verify the password
    if ($validUsers[$username] === $password) {
      // Login successful - set a session variable to indicate logged-in status
      session_start();
      $_SESSION['user_id'] = $username; // Use username as identifier
      $_SESSION['logged_in'] = true;
      return true;
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // Username not found
  }
}

// Example usage:
// Simulate a login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo '<p>Login successful! You are now logged in.</p>';
    // Redirect to a protected page or display a welcome message
    header("Location: welcome.php"); // Redirect to welcome page
    exit();
  } else {
    echo '<p>Invalid username or password.</p>';
  }
}
?>
