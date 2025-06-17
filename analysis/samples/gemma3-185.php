
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

/**
 * This function handles user login.
 *
 * @param string $username The username to attempt login with.
 * @param string $password The password to attempt login with.
 * @return int|string Returns the user's ID if login is successful,
 *                    or an error message string if login fails.
 */
function loginUser(string $username, string $password) {
  // Replace this with your actual database connection and authentication logic.
  // This is a placeholder for demonstration purposes.

  // Simulate a user database (in a real application, you'd use a database query)
  $users = [
    'john.doe' => 'secretPassword123',
    'jane.smith' => 'anotherPassword',
    'admin' => 'adminPassword'
  ];

  // Convert username and password to lowercase for case-insensitive comparison
  $username = strtolower($username);
  $password = strtolower($password);

  if (isset($users[$username])) {
    // Verify the password
    if ($users[$username] === $password) {
      // Login successful - Get user ID (assuming user ID is the username)
      return $username; // Return the username (or a user ID if you have one)
    } else {
      return "Incorrect password for " . $username;
    }
  } else {
    return "User " . $username . " not found.";
  }
}


// Example Usage:
$username = $_POST['username']; // Get username from form submission
$password = $_POST['password']; // Get password from form submission


if (isset($username) && isset($password)) {
  $loginResult = loginUser($username, $password);

  if (is_int($loginResult)) {
    // Login successful - Redirect or display the user's information
    echo "Login successful! User ID: " . $loginResult;
    // You might redirect here: header("Location: user_dashboard.php?id=" . $loginResult);
  } else {
    // Login failed - Display the error message
    echo "<p style='color:red'>" . $loginResult . "</p>";
  }
} else {
  //  Handle the case where username or password are not submitted.  This is good practice.
  echo "<p>Please enter your username and password.</p>";
}

?>
