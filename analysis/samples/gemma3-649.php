    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
session_start();

/**
 * Logs a user into the application.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
  // In a real application, you would fetch this from a database.
  // For this example, we'll use a hardcoded user and password.
  $validUsers = [
    'john.doe' => 'password123',
    'jane.smith' => 'secretpass',
  ];

  // Check if the username exists
  if (isset($validUsers[$username])) {
    // Verify the password
    if (password_verify($password, $validUsers[$username])) {
      // Password is correct, set session variables
      $_SESSION['user_id'] = $username; // Store username as user_id (more secure)
      $_SESSION['is_logged_in'] = true;
      return true;
    } else {
      return false; // Password mismatch
    }
  } else {
    return false; // Username doesn't exist
  }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page or display a welcome message.
    header("Location: /protected_page.php"); // Replace with the actual URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
