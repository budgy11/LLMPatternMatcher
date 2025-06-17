

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
session_start();

/**
 * Handles user login.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to authenticate.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // In a real application, you would store the password securely (hashed)
  // and compare the hashed password with the provided password.
  // This example demonstrates a simple (and insecure) comparison for demonstration purposes.

  // Hardcoded credentials (FOR DEMO ONLY - NEVER DO THIS IN PRODUCTION!)
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass42'
  ];

  if (isset($validUsers[$username])) {
    if ($validUsers[$username] == $password) {
      // Login successful
      $_SESSION['username'] = $username;
      return true;
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }
}

// --- Example Usage (Demonstration) ---

// Check if the user is already logged in
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php");  // Replace with your actual protected page
    exit(); // Important to stop further execution after redirection
  } else {
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
