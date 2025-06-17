
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
  </form>

  <a href="logout.php" style="color: red;">Logout</a>  <!-- This would be a separate file for logout -->

</body>
</html>


<?php
session_start();

/**
 * Handles user login functionality.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  //  In a real application, you would check against a database.
  //  This is a simplified example for demonstration.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'secretpass',
  ];

  if (isset($validUsers[$username])) {
    if ($validUsers[$username] === $password) {
      //  Login successful
      $_SESSION['username'] = $username; // Store username in session
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// Example Usage (demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a secure page or display a welcome message.
    header("Location: /welcome.php"); // Replace /welcome.php with your welcome page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
